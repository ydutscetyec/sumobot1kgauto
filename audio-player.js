(function() {
    'use strict';

    const styles = `
        .ab-audio-player {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            transition: background 0.3s, border-color 0.3s;
        }
        
        .ab-ap-header {
            font-size: 14px;
            color: #333333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .ab-ap-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .ab-btn {
            background: #4a90d9;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            line-height: 1;
        }
        .ab-btn:hover { background: #357abd; }
        .ab-status {
            margin-top: 12px;
            font-size: 13px;
            color: #555555;
        }
        .ab-powered {
            margin-top: 10px;
            text-align: right;
            font-size: 11px;
            color: #888888;
        }
        .ab-powered em { font-style: italic; color: #666666; }
        .ab-speed {
            padding: 6px 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 13px;
            background: #ffffff;
            color: #333333;
            cursor: pointer;
        }
        .ab-speed option {
            background: #ffffff;
            color: #333333;
        }

        /* Only apply dark mode when page is actually in dark mode */
        body.dark-mode .ab-audio-player,
        body[data-theme="dark"] .ab-audio-player,
        .dark-theme .ab-audio-player,
        [data-theme="dark"] .ab-audio-player {
            background: #2a2a2a;
            border-color: #444444;
        }
        body.dark-mode .ab-ap-header,
        body[data-theme="dark"] .ab-ap-header,
        .dark-theme .ab-ap-header,
        [data-theme="dark"] .ab-ap-header {
            color: #ffffff;
        }
        body.dark-mode .ab-status,
        body[data-theme="dark"] .ab-status,
        .dark-theme .ab-status,
        [data-theme="dark"] .ab-status {
            color: #cccccc;
        }
        body.dark-mode .ab-powered,
        body[data-theme="dark"] .ab-powered,
        .dark-theme .ab-powered,
        [data-theme="dark"] .ab-powered {
            color: #999999;
        }
        body.dark-mode .ab-powered em,
        body[data-theme="dark"] .ab-powered em,
        .dark-theme .ab-powered em,
        [data-theme="dark"] .ab-powered em {
            color: #aaaaaa;
        }
        body.dark-mode .ab-speed,
        body[data-theme="dark"] .ab-speed,
        .dark-theme .ab-speed,
        [data-theme="dark"] .ab-speed {
            background: #3a3a3a;
            color: #ffffff;
            border-color: #555555;
        }
        body.dark-mode .ab-speed option,
        body[data-theme="dark"] .ab-speed option,
        .dark-theme .ab-speed option,
        [data-theme="dark"] .ab-speed option {
            background: #3a3a3a;
            color: #ffffff;
        }
    `;

    const styleSheet = document.createElement('style');
    styleSheet.textContent = styles;
    document.head.appendChild(styleSheet);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAudioPlayer);
    } else {
        initAudioPlayer();
    }

    function initAudioPlayer() {
        const playerDiv = document.getElementById('audioPlayer');
        if (!playerDiv) return;

        const articleContent = document.querySelector('.article-content');
        if (!articleContent) return;

        const text = articleContent.innerText.trim();
        if (!text) return;

        playerDiv.innerHTML = `
            <div class="ab-audio-player">
                <div class="ab-ap-header">Pindutin ang play upang makinig</div>
                <div class="ab-ap-controls">
                    <button id="abPlayBtn" class="ab-btn" title="Play">▶</button>
                    <button id="abPauseBtn" class="ab-btn" style="display:none;" title="Pause">⏸</button>
                    <button id="abStopBtn" class="ab-btn" title="Stop">⏹</button>
                    <select id="abSpeed" class="ab-speed" title="Playback speed">
                        <option value="0.8">0.8x</option>
                        <option value="1" selected>1x</option>
                        <option value="1.2">1.2x</option>
                        <option value="1.5">1.5x</option>
                    </select>
                    <button id="abMuteBtn" class="ab-btn" title="Mute/Unmute">🔊</button>
                </div>
                <div id="abStatus" class="ab-status">Handa na</div>
                <div class="ab-powered">Powered by <em>ResponsiveVoice</em></div>
            </div>
        `;

        setupPlayer(text);
    }

    function setupPlayer(text) {
        const playBtn = document.getElementById('abPlayBtn');
        const pauseBtn = document.getElementById('abPauseBtn');
        const stopBtn = document.getElementById('abStopBtn');
        const muteBtn = document.getElementById('abMuteBtn');
        const speedSelect = document.getElementById('abSpeed');
        const statusEl = document.getElementById('abStatus');

        let isPlaying = false;
        let isMuted = false;

        if (typeof responsiveVoice === 'undefined') {
            statusEl.textContent = 'Error: Audio service hindi pa loaded';
            statusEl.style.color = '#ff4444';
            return;
        }

        const voice = 'Filipino Female';

        playBtn.addEventListener('click', () => {
            if (!isPlaying) {
                const rate = parseFloat(speedSelect.value);
                const volume = isMuted ? 0 : 1;

                responsiveVoice.speak(text, voice, {
                    rate: rate,
                    pitch: 1,
                    volume: volume,
                    onstart: () => {
                        isPlaying = true;
                        playBtn.style.display = 'none';
                        pauseBtn.style.display = 'flex';
                        statusEl.textContent = 'Nagsasalita...';
                        statusEl.style.color = '#4caf50';
                    },
                    onend: () => {
                        isPlaying = false;
                        playBtn.style.display = 'flex';
                        pauseBtn.style.display = 'none';
                        statusEl.textContent = 'Tapos na';
                        statusEl.style.color = '';
                    },
                    onerror: () => {
                        isPlaying = false;
                        playBtn.style.display = 'flex';
                        pauseBtn.style.display = 'none';
                        statusEl.textContent = 'May error sa audio';
                        statusEl.style.color = '#ff4444';
                    }
                });
            }
        });

        pauseBtn.addEventListener('click', () => {
            if (isPlaying) {
                responsiveVoice.cancel();
                isPlaying = false;
                playBtn.style.display = 'flex';
                pauseBtn.style.display = 'none';
                statusEl.textContent = 'Naka-pause';
                statusEl.style.color = '#ffaa00';
            }
        });

        stopBtn.addEventListener('click', () => {
            responsiveVoice.cancel();
            isPlaying = false;
            playBtn.style.display = 'flex';
            pauseBtn.style.display = 'none';
            statusEl.textContent = 'Tigil na';
            statusEl.style.color = '';
        });

        muteBtn.addEventListener('click', () => {
            isMuted = !isMuted;
            muteBtn.textContent = isMuted ? '🔇' : '🔊';
            if (isPlaying) {
                responsiveVoice.setVolume(isMuted ? 0 : 1);
            }
            statusEl.textContent = isMuted ? 'Naka-mute' : 'Nagsasalita...';
        });

        speedSelect.addEventListener('change', () => {
            if (isPlaying) {
                responsiveVoice.cancel();
                isPlaying = false;
                playBtn.style.display = 'flex';
                pauseBtn.style.display = 'none';
                setTimeout(() => playBtn.click(), 100);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && e.target.tagName !== 'SELECT') {
                e.preventDefault();
                if (isPlaying) pauseBtn.click();
                else playBtn.click();
            }
        });
    }
})();