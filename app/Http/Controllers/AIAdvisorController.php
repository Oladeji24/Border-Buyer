<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIAdvisorController extends Controller
{
    /**
     * AI Advisor endpoint for transaction analysis
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'description' => 'required|string|max:500',
            'type' => 'string|in:escrow,monitoring,general|default:general'
        ]);

        $description = $request->input('description');
        $type = $request->input('type', 'general');

        // Check which AI service to use
        $aiService = config('services.ai.service', 'dummy');

        try {
            switch ($aiService) {
                case 'openai':
                    $analysis = $this->analyzeWithOpenAI($description, $type);
                    break;
                case 'anthropic':
                    $analysis = $this->analyzeWithAnthropic($description, $type);
                    break;
                case 'google':
                    $analysis = $this->analyzeWithGoogleAI($description, $type);
                    break;
                default:
                    // Fallback to dummy analysis if no AI service is configured
                    $analysis = $this->generateDummyAnalysis($description, $type);
                    break;
            }

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'service_used' => $aiService,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            // Log the error and fallback to dummy analysis
            \Log::error('AI Service Error', [
                'service' => $aiService,
                'error' => $e->getMessage(),
                'description' => $description,
                'type' => $type
            ]);

            // Fallback to dummy analysis
            $analysis = $this->generateDummyAnalysis($description, $type);

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'service_used' => 'dummy_fallback',
                'warning' => 'AI service unavailable, using fallback analysis',
                'timestamp' => now()->toISOString()
            ]);
        }
    }

    /**
     * Generate dummy AI analysis for demo purposes
     *
     * @param string $description
     * @param string $type
     * @return array
     */
    private function generateDummyAnalysis(string $description, string $type): array
    {
        // Risk assessment based on keywords
        $riskKeywords = ['high value', 'urgent', 'wire transfer', 'crypto', 'gift card', 'advance payment'];
        $safeKeywords = ['verified', 'escrow', 'monitoring', 'agent', 'inspection', 'documentation'];
        
        $riskLevel = 'low';
        $riskScore = 0;
        
        // Simple keyword analysis
        foreach ($riskKeywords as $keyword) {
            if (stripos($description, $keyword) !== false) {
                $riskScore += 2;
            }
        }
        
        foreach ($safeKeywords as $keyword) {
            if (stripos($description, $keyword) !== false) {
                $riskScore -= 1;
            }
        }
        
        if ($riskScore >= 3) {
            $riskLevel = 'high';
        } elseif ($riskScore >= 1) {
            $riskLevel = 'medium';
        }

        // Generate recommendations based on type
        $recommendations = [];
        
        switch ($type) {
            case 'escrow':
                $recommendations = [
                    'Use our secure escrow service to hold funds',
                    'Request detailed documentation from seller',
                    'Arrange for third-party inspection before release'
                ];
                break;
            case 'monitoring':
                $recommendations = [
                    'Enable real-time transaction monitoring',
                    'Set up alerts for any unusual activity',
                    'Use verified agents for quality control'
                ];
                break;
            default:
                $recommendations = [
                    'Verify seller credentials and reputation',
                    'Request detailed product documentation',
                    'Consider using our escrow service for security',
                    'Arrange for independent inspection if possible'
                ];
        }

        return [
            'risk_level' => $riskLevel,
            'risk_score' => $riskScore,
            'summary' => $this->generateSummary($description, $riskLevel),
            'recommendations' => $recommendations,
            'key_factors' => $this->extractKeyFactors($description),
            'next_steps' => $this->generateNextSteps($riskLevel, $type)
        ];
    }

    /**
     * Generate a summary based on risk level
     *
     * @param string $description
     * @param string $riskLevel
     * @return string
     */
    private function generateSummary(string $description, string $riskLevel): string
    {
        $summaries = [
            'low' => "This transaction appears to have low risk factors. Standard due diligence is recommended.",
            'medium' => "This transaction has some risk factors that warrant additional verification and monitoring.",
            'high' => "This transaction has significant risk factors. We strongly recommend enhanced due diligence and using our escrow service."
        ];

        return $summaries[$riskLevel] ?? $summaries['low'];
    }

    /**
     * Extract key factors from description
     *
     * @param string $description
     * @return array
     */
    private function extractKeyFactors(string $description): array
    {
        $factors = [];
        
        // Extract country mentions
        if (preg_match('/\b(USA|Canada|Mexico|Colombia|Brazil|UK|Germany|France|China|India|UAE|Nigeria|Ghana|Kenya)\b/i', $description, $matches)) {
            $factors[] = 'Cross-border transaction to ' . $matches[1];
        }
        
        // Extract product type
        if (preg_match('/\b(coffee|electronics|textiles|machinery|vehicles|agricultural|pharmaceutical|construction)\b/i', $description, $matches)) {
            $factors[] = 'Product type: ' . ucfirst($matches[1]);
        }
        
        // Extract value indicators
        if (preg_match('/\$(\d+(?:,\d{3})*(?:\.\d{2})?)/', $description, $matches)) {
            $factors[] = 'Transaction value: $' . $matches[1];
        }
        
        if (empty($factors)) {
            $factors[] = 'Standard transaction parameters';
        }
        
        return $factors;
    }

    /**
     * Generate next steps based on risk level
     *
     * @param string $riskLevel
     * @param string $type
     * @return array
     */
    private function generateNextSteps(string $riskLevel, string $type): array
    {
        $steps = [
            'low' => [
                'Proceed with standard verification',
                'Request basic documentation',
                'Consider using our monitoring service'
            ],
            'medium' => [
                'Request detailed seller verification',
                'Use our escrow service for security',
                'Arrange for third-party inspection',
                'Set up transaction monitoring alerts'
            ],
            'high' => [
                'Contact our verified agents for assistance',
                'Use our full escrow and monitoring package',
                'Request comprehensive documentation',
                'Consider phased payment structure',
                'Arrange for independent quality inspection'
            ]
        ];

        return $steps[$riskLevel] ?? $steps['low'];
    }

    /**
     * Analyze transaction using OpenAI GPT
     *
     * @param string $description
     * @param string $type
     * @return array
     */
    private function analyzeWithOpenAI(string $description, string $type): array
    {
        $apiKey = config('services.ai.openai_api_key');
        $model = config('services.ai.openai_model', 'gpt-3.5-turbo');

        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $prompt = $this->buildAnalysisPrompt($description, $type);

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert transaction risk analyst for cross-border trade. Analyze transactions and provide detailed risk assessments with practical recommendations.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3,
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        $aiResponse = $result['choices'][0]['message']['content'] ?? '';

        return $this->parseAIResponse($aiResponse);
    }

    /**
     * Analyze transaction using Anthropic Claude
     *
     * @param string $description
     * @param string $type
     * @return array
     */
    private function analyzeWithAnthropic(string $description, string $type): array
    {
        $apiKey = config('services.ai.anthropic_api_key');
        $model = config('services.ai.anthropic_model', 'claude-3-sonnet-20240229');

        if (!$apiKey) {
            throw new \Exception('Anthropic API key not configured');
        }

        $prompt = $this->buildAnalysisPrompt($description, $type);

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01',
            ],
            'json' => [
                'model' => $model,
                'max_tokens' => 500,
                'temperature' => 0.3,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        $aiResponse = $result['content'][0]['text'] ?? '';

        return $this->parseAIResponse($aiResponse);
    }

    /**
     * Analyze transaction using Google AI
     *
     * @param string $description
     * @param string $type
     * @return array
     */
    private function analyzeWithGoogleAI(string $description, string $type): array
    {
        $apiKey = config('services.ai.google_api_key');
        $model = config('services.ai.google_model', 'gemini-pro');

        if (!$apiKey) {
            throw new \Exception('Google AI API key not configured');
        }

        $prompt = $this->buildAnalysisPrompt($description, $type);

        $client = new \GuzzleHttp\Client();
        $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 500,
                ]
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return $this->parseAIResponse($aiResponse);
    }

    /**
     * Build the analysis prompt for AI services
     *
     * @param string $description
     * @param string $type
     * @return string
     */
    private function buildAnalysisPrompt(string $description, string $type): string
    {
        $typeSpecific = match($type) {
            'escrow' => 'Focus on escrow-specific risks and recommendations.',
            'monitoring' => 'Focus on transaction monitoring and fraud detection.',
            default => 'Provide general transaction risk assessment.'
        };

        return "Analyze the following cross-border transaction description and provide a detailed risk assessment:\n\n" .
               "Transaction Description: {$description}\n" .
               "Analysis Type: {$type}\n" .
               "Special Focus: {$typeSpecific}\n\n" .
               "Please provide your response in JSON format with the following structure:\n" .
               "{\n" .
               "  \"risk_level\": \"low|medium|high\",\n" .
               "  \"risk_score\": 0-10,\n" .
               "  \"summary\": \"Brief summary of risk assessment\",\n" .
               "  \"recommendations\": [\"recommendation1\", \"recommendation2\", \"recommendation3\"],\n" .
               "  \"key_factors\": [\"factor1\", \"factor2\", \"factor3\"],\n" .
               "  \"next_steps\": [\"step1\", \"step2\", \"step3\"]\n" .
               "}\n\n" .
               "Consider factors such as transaction value, countries involved, payment methods, time pressure, and any red flags in the description.";
    }

    /**
     * Parse AI response into structured format
     *
     * @param string $aiResponse
     * @return array
     */
    private function parseAIResponse(string $aiResponse): array
    {
        try {
            // Try to parse as JSON first
            $data = json_decode($aiResponse, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return [
                    'risk_level' => $data['risk_level'] ?? 'medium',
                    'risk_score' => $data['risk_score'] ?? 5,
                    'summary' => $data['summary'] ?? 'AI analysis completed',
                    'recommendations' => $data['recommendations'] ?? ['Standard verification recommended'],
                    'key_factors' => $data['key_factors'] ?? ['Transaction analysis completed'],
                    'next_steps' => $data['next_steps'] => ['Proceed with standard verification'],
                ];
            }
        } catch (\Exception $e) {
            // If JSON parsing fails, continue to fallback
        }

        // Fallback: parse text response
        return [
            'risk_level' => $this->extractRiskLevel($aiResponse),
            'risk_score' => $this->extractRiskScore($aiResponse),
            'summary' => substr($aiResponse, 0, 200) . '...',
            'recommendations' => ['AI-based analysis completed'],
            'key_factors' => ['AI-powered risk assessment'],
            'next_steps' => ['Follow AI recommendations'],
        ];
    }

    /**
     * Extract risk level from AI response
     */
    private function extractRiskLevel(string $response): string
    {
        if (preg_match('/high/i', $response)) return 'high';
        if (preg_match('/medium/i', $response)) return 'medium';
        return 'low';
    }

    /**
     * Extract risk score from AI response
     */
    private function extractRiskScore(string $response): int
    {
        if (preg_match('/(\d+)/', $response, $matches)) {
            return min(10, max(0, (int)$matches[1]));
        }
        return 5;
    }
}
