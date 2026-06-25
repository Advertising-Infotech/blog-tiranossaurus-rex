/* ===== API Fallback System ===== */
(function() {
    'use strict';

    const API_KEYS = (function() {
        try {
            var stored = localStorage.getItem('trex_api_keys');
            return stored ? JSON.parse(stored) : [];
        } catch(e) { return []; }
    })();

    const API_ENDPOINTS = [
        { name: 'OpenAI',      url: 'https://api.openai.com/v1/chat/completions',    model: 'gpt-3.5-turbo' },
        { name: 'OpenAI GPT4', url: 'https://api.openai.com/v1/chat/completions',    model: 'gpt-4' },
        { name: 'Claude',      url: 'https://api.anthropic.com/v1/messages',          model: 'claude-3-haiku-20240307' },
        { name: 'Gemini',      url: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent', model: 'gemini-pro' },
        { name: 'DeepSeek',    url: 'https://api.deepseek.com/v1/chat/completions',   model: 'deepseek-chat' },
        { name: 'Mistral',     url: 'https://api.mistral.ai/v1/chat/completions',     model: 'mistral-small-latest' },
        { name: 'Groq',        url: 'https://api.groq.com/openai/v1/chat/completions', model: 'mixtral-8x7b-32768' },
        { name: 'Together',    url: 'https://api.together.xyz/v1/chat/completions',   model: 'mistralai/Mixtral-8x7B-Instruct-v0.1' },
        { name: 'Perplexity',  url: 'https://api.perplexity.ai/chat/completions',     model: 'sonar-small-chat' },
        { name: 'Cohere',      url: 'https://api.cohere.ai/v1/chat',                  model: 'command' },
        { name: 'AI21',        url: 'https://api.ai21.com/studio/v1/chat/completions', model: 'j2-ultra' },
        { name: 'HuggingFace', url: 'https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf/v1/chat/completions', model: 'llama2-7b' },
        { name: 'Replicate',   url: 'https://api.replicate.com/v1/predictions',       model: 'meta/llama-2-70b-chat' },
        { name: 'OpenRouter',  url: 'https://openrouter.ai/api/v1/chat/completions',  model: 'openai/gpt-3.5-turbo' },
        { name: 'Fireworks',   url: 'https://api.fireworks.ai/inference/v1/chat/completions', model: 'accounts/fireworks/models/llama-v2-7b-chat' },
        { name: 'Lepton',      url: 'https://api.lepton.ai/v1/chat/completions',      model: 'llama2-7b' },
    ];

    var currentKeyIndex = 0;
    var currentEndpointIndex = 0;

    function updateStatus(text, type) {
        var redTextEl = document.getElementById('status-text-red');
        if (redTextEl) {
            redTextEl.textContent = text;
        }
    }

    async function queryAPI(prompt, onChunk) {
        var totalKeys = API_KEYS.length;
        if (totalKeys === 0) {
            updateStatus('Nenhuma chave API configurada — vá em /admin', 'offline');
            return null;
        }

        for (var attempt = 0; attempt < totalKeys * API_ENDPOINTS.length; attempt++) {
            var keyIndex = (currentKeyIndex + attempt) % totalKeys;
            var epIndex = (currentEndpointIndex + Math.floor(attempt / totalKeys)) % API_ENDPOINTS.length;
            var apiKey = API_KEYS[keyIndex];
            var endpoint = API_ENDPOINTS[epIndex];

            updateStatus('Tentando ' + endpoint.name + ' (key #' + (keyIndex + 1) + ')...', 'fallback');

            try {
                var headers = { 'Content-Type': 'application/json' };
                var body = {};

                if (endpoint.name === 'Claude') {
                    headers['x-api-key'] = apiKey;
                    headers['anthropic-version'] = '2023-06-01';
                    body = {
                        model: endpoint.model,
                        max_tokens: 1024,
                        messages: [{ role: 'user', content: prompt }]
                    };
                } else if (endpoint.name === 'Gemini') {
                    var geminiUrl = endpoint.url + '?key=' + apiKey;
                    body = { contents: [{ parts: [{ text: prompt }] }] };
                    var res = await fetch(geminiUrl, { method: 'POST', headers: headers, body: JSON.stringify(body) });
                    var data = await res.json();
                    if (data.candidates && data.candidates[0]) {
                        var text = data.candidates[0].content.parts[0].text;
                        if (onChunk) onChunk(text);
                        currentKeyIndex = keyIndex;
                        currentEndpointIndex = epIndex;
                        updateStatus('Online: ' + endpoint.name, 'online');
                        return text;
                    }
                    continue;
                } else if (endpoint.name === 'Replicate') {
                    headers['Authorization'] = 'Bearer ' + apiKey;
                    body = { input: { prompt: prompt }, version: endpoint.model };
                    var res = await fetch(endpoint.url, { method: 'POST', headers: headers, body: JSON.stringify(body) });
                    var data = await res.json();
                    if (data.urls && data.urls.get) {
                        updateStatus('Processando em ' + endpoint.name + '...', 'fallback');
                        for (var w = 0; w < 30; w++) {
                            await new Promise(r => setTimeout(r, 1000));
                            var check = await fetch(data.urls.get, { headers: headers });
                            var checkData = await check.json();
                            if (checkData.status === 'succeeded') {
                                var output = Array.isArray(checkData.output) ? checkData.output.join('') : (checkData.output || '');
                                if (onChunk) onChunk(output);
                                currentKeyIndex = keyIndex;
                                currentEndpointIndex = epIndex;
                                updateStatus('Online: ' + endpoint.name, 'online');
                                return output;
                            }
                        }
                    }
                    continue;
                } else if (endpoint.name === 'Cohere') {
                    headers['Authorization'] = 'Bearer ' + apiKey;
                    body = { message: prompt, model: endpoint.model };
                } else {
                    headers['Authorization'] = 'Bearer ' + apiKey;
                    body = { model: endpoint.model, messages: [{ role: 'user', content: prompt }], max_tokens: 1024 };
                }

                var res = await fetch(endpoint.url, { method: 'POST', headers: headers, body: JSON.stringify(body) });

                if (!res.ok) {
                    if (res.status === 429 || res.status >= 500) {
                        continue;
                    }
                    continue;
                }

                var data = await res.json();
                var result = '';

                if (data.choices && data.choices[0]) {
                    result = data.choices[0].message ? data.choices[0].message.content : data.choices[0].text;
                } else if (data.content && data.content[0]) {
                    result = data.content[0].text;
                } else if (data.generations && data.generations[0]) {
                    result = data.generations[0].text;
                } else if (data.output) {
                    result = typeof data.output === 'string' ? data.output : JSON.stringify(data.output);
                } else {
                    result = JSON.stringify(data);
                }

                if (onChunk) onChunk(result);
                currentKeyIndex = keyIndex;
                currentEndpointIndex = epIndex;
                updateStatus('Online: ' + endpoint.name, 'online');
                return result;

            } catch(e) {
                continue;
            }
        }

        updateStatus('Todas as APIs falharam', 'offline');
        return null;
    }

    window.TREX_API = {
        query: queryAPI,
        updateStatus: updateStatus,
        getKeys: function() { return API_KEYS; },
        setKeys: function(keys) {
            API_KEYS.length = 0;
            keys.forEach(function(k) { API_KEYS.push(k); });
            try { localStorage.setItem('trex_api_keys', JSON.stringify(keys)); } catch(e) {}
        }
    };

})();
