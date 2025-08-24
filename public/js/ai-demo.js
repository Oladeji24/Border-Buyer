// AI Demo Widget JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const aiDemoForm = document.querySelector('form[onsubmit*="event.preventDefault()"]');

    if (aiDemoForm) {
        // Override the form submission
        aiDemoForm.onsubmit = function(event) {
            event.preventDefault();

            // Get the transaction description
            const descriptionInput = document.getElementById('transaction_description');
            const description = descriptionInput.value.trim();

            if (!description) {
                alert('Please enter a transaction description');
                return;
            }

            // Get the analyze button
            const analyzeButton = aiDemoForm.querySelector('button[type="submit"]');
            const originalButtonText = analyzeButton.textContent;

            // Show loading state
            analyzeButton.disabled = true;
            analyzeButton.textContent = 'Analyzing...';

            // Make API request to /advisor endpoint
            fetch('/api/advisor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    description: description,
                    type: 'general' // Default type, can be enhanced later
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAnalysisResults(data.analysis);
                } else {
                    alert('Error analyzing transaction. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error connecting to the AI service. Please try again.');
            })
            .finally(() => {
                // Reset button state
                analyzeButton.disabled = false;
                analyzeButton.textContent = originalButtonText;
            });
        };
    }

    // Function to display analysis results
    function displayAnalysisResults(analysis) {
        // Check if results container already exists
        let resultsContainer = document.getElementById('ai-analysis-results');

        // If not, create it
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.id = 'ai-analysis-results';
            resultsContainer.className = 'mt-8 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md';

            // Insert after the form
            const aiDemoSection = document.querySelector('.bg-gray-50');
            if (aiDemoSection) {
                aiDemoSection.appendChild(resultsContainer);
            }
        }

        // Determine risk level color
        let riskColor = 'green';
        if (analysis.risk_level === 'medium') {
            riskColor = 'yellow';
        } else if (analysis.risk_level === 'high') {
            riskColor = 'red';
        }

        // Build the HTML for the results
        let resultsHTML = `
            <h3 class="text-lg font-medium text-gray-900 mb-4">AI Analysis Results</h3>
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-${riskColor}-100 text-${riskColor}-800">
                    Risk Level: ${analysis.risk_level.charAt(0).toUpperCase() + analysis.risk_level.slice(1)}
                </span>
            </div>
            <div class="mb-4">
                <h4 class="text-md font-medium text-gray-900 mb-2">Summary</h4>
                <p class="text-gray-600">${analysis.summary}</p>
            </div>
        `;

        // Add key factors if any
        if (analysis.key_factors && analysis.key_factors.length > 0) {
            resultsHTML += `
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Key Factors</h4>
                    <ul class="list-disc pl-5 text-gray-600">
            `;

            analysis.key_factors.forEach(factor => {
                resultsHTML += `<li>${factor}</li>`;
            });

            resultsHTML += `
                    </ul>
                </div>
            `;
        }

        // Add recommendations
        if (analysis.recommendations && analysis.recommendations.length > 0) {
            resultsHTML += `
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Recommendations</h4>
                    <ul class="list-disc pl-5 text-gray-600">
            `;

            analysis.recommendations.forEach(recommendation => {
                resultsHTML += `<li>${recommendation}</li>`;
            });

            resultsHTML += `
                    </ul>
                </div>
            `;
        }

        // Add next steps
        if (analysis.next_steps && analysis.next_steps.length > 0) {
            resultsHTML += `
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-2">Next Steps</h4>
                    <ul class="list-disc pl-5 text-gray-600">
            `;

            analysis.next_steps.forEach(step => {
                resultsHTML += `<li>${step}</li>`;
            });

            resultsHTML += `
                    </ul>
                </div>
            `;
        }

        // Update the results container
        resultsContainer.innerHTML = resultsHTML;

        // Scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
