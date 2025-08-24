<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    /**
     * Maximum file sizes in bytes
     */
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_DOCUMENT_SIZE = 10 * 1024 * 1024; // 10MB
    const MAX_VIDEO_SIZE = 50 * 1024 * 1024; // 50MB

    /**
     * Allowed file types
     */
    const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
    const ALLOWED_VIDEO_TYPES = ['mp4', 'avi', 'mov', 'wmv', 'flv'];

    /**
     * Upload a file with security validation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // 50MB max
            'type' => 'required|in:image,document,video',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->input('type');
            $category = $request->input('category', 'general');
            
            // Security validations
            $this->validateFileSecurity($file, $type);

            // Generate secure filename
            $filename = $this->generateSecureFilename($file, $type);
            
            // Determine storage path
            $path = $this->getStoragePath($type, $category);
            
            // Process and store file
            $storedPath = $this->processAndStoreFile($file, $filename, $path, $type);
            
            // Create file record
            $fileRecord = $this->createFileRecord($file, $storedPath, $type, $category, $request);

            Log::info('File uploaded successfully', [
                'file_id' => $fileRecord->id,
                'filename' => $filename,
                'type' => $type,
                'size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $fileRecord->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'url' => Storage::url($storedPath),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $type,
                    'category' => $category,
                    'created_at' => $fileRecord->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $request->file('file')?->getClientOriginalName(),
                'type' => $request->input('type'),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate file security
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $type
     * @return void
     * @throws \Exception
     */
    protected function validateFileSecurity($file, $type)
    {
        // Check file size
        $maxSize = match($type) {
            'image' => self::MAX_IMAGE_SIZE,
            'document' => self::MAX_DOCUMENT_SIZE,
            'video' => self::MAX_VIDEO_SIZE,
            default => self::MAX_DOCUMENT_SIZE,
        };

        if ($file->getSize() > $maxSize) {
            throw new \Exception("File size exceeds maximum limit of " . ($maxSize / 1024 / 1024) . "MB");
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = match($type) {
            'image' => self::ALLOWED_IMAGE_TYPES,
            'document' => self::ALLOWED_DOCUMENT_TYPES,
            'video' => self::ALLOWED_VIDEO_TYPES,
            default => [],
        };

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception("File type not allowed. Allowed types: " . implode(', ', $allowedTypes));
        }

        // Check MIME type
        $mimeTypes = match($type) {
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'text/rtf'],
            'video' => ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv', 'video/x-flv'],
            default => [],
        };

        if (!in_array($file->getMimeType(), $mimeTypes)) {
            throw new \Exception("File MIME type not allowed");
        }

        // Scan for malicious content (basic check)
        if ($this->containsMaliciousContent($file)) {
            throw new \Exception("File contains potentially malicious content");
        }
    }

    /**
     * Generate secure filename
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $type
     * @return string
     */
    protected function generateSecureFilename($file, $type)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $randomString = Str::random(40);
        $timestamp = time();
        
        return "{$type}/{$timestamp}_{$randomString}.{$extension}";
    }

    /**
     * Get storage path
     *
     * @param  string  $type
     * @param  string  $category
     * @return string
     */
    protected function getStoragePath($type, $category)
    {
        return "uploads/{$type}/" . date('Y/m/d') . "/{$category}";
    }

    /**
     * Process and store file
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $filename
     * @param  string  $path
     * @param  string  $type
     * @return string
     */
    protected function processAndStoreFile($file, $filename, $path, $type)
    {
        $fullPath = $path . '/' . basename($filename);

        if ($type === 'image') {
            // Process image
            $image = Image::make($file);
            
            // Auto-orient image
            $image->orientate();
            
            // Resize if too large (max 1920x1080)
            if ($image->width() > 1920 || $image->height() > 1080) {
                $image->resize(1920, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Optimize image
            $image->encode(null, 75); // 75% quality
            
            // Store processed image
            Storage::disk('s3')->put($fullPath, (string) $image, 'public');
        } else {
            // Store file as-is
            Storage::disk('s3')->putFileAs($path, $file, basename($filename), 'public');
        }

        return $fullPath;
    }

    /**
     * Create file record
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $path
     * @param  string  $type
     * @param  string  $category
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\UserFile
     */
    protected function createFileRecord($file, $path, $type, $category, $request)
    {
        return auth()->user()->files()->create([
            'filename' => basename($path),
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type,
            'category' => $category,
            'description' => $request->input('description'),
            'hash' => hash_file('sha256', $file->getPathname()),
            'is_processed' => $type === 'image', // Images are processed
            'metadata' => json_encode([
                'dimensions' => $type === 'image' ? $this->getImageDimensions($file) : null,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ]),
        ]);
    }

    /**
     * Check for malicious content
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return bool
     */
    protected function containsMaliciousContent($file)
    {
        // Basic check for PHP tags in files that shouldn't contain them
        $content = file_get_contents($file->getPathname());
        
        // Check for PHP tags
        if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
            return true;
        }

        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/eval\(/i',
            '/base64_decode\(/i',
            '/shell_exec\(/i',
            '/exec\(/i',
            '/system\(/i',
            '/passthru\(/i',
            '/\$_GET/i',
            '/\$_POST/i',
            '/\$_REQUEST/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get image dimensions
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return array|null
     */
    protected function getImageDimensions($file)
    {
        try {
            $image = Image::make($file);
            return [
                'width' => $image->width(),
                'height' => $image->height(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete a file
     *
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($fileId)
    {
        try {
            $file = auth()->user()->files()->findOrFail($fileId);
            
            // Delete from storage
            Storage::disk('s3')->delete($file->path);
            
            // Delete record
            $file->delete();

            Log::info('File deleted successfully', [
                'file_id' => $fileId,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'file_id' => $fileId,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}