document.addEventListener('DOMContentLoaded', function () {
   
    // Load components first, then initialize all functionality
    loadComponents().then(() => {
        // Initialize theme system
        initializeThemeSystem();
        
        // Initialize header interactions
        initializeHeaderInteractions();
        
        // Initialize sidebar interactions
        initializeSidebarInteractions();

        // Initialize footer interactions
        initializeFooterInteractions();
        
    });
});

// Load header, sidebar, and dashboard content with Promise
function loadComponents() {
    return new Promise((resolve) => {
        const loadPromises = [];
        
        // Load header
        const headerPromise = fetch('../user/header.php')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load header');
                return response.text();
            })
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
                console.log('Header loaded successfully');
            })
            .catch(error => {
                console.error('Error loading header:', error);
                document.getElementById('header-container').innerHTML = '<div>Header failed to load</div>';
            });
        loadPromises.push(headerPromise);
        
        // Load sidebar
        const sidebarPromise = fetch('../user/sidebar.php')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load sidebar');
                return response.text();
            })
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
                console.log('Sidebar loaded successfully');
            })
            .catch(error => {
                console.error('Error loading sidebar:', error);
                document.getElementById('sidebar-container').innerHTML = '<div>Sidebar failed to load</div>';
            });
        loadPromises.push(sidebarPromise);

        // Load footer 
        const footerPromise = fetch('footer.html')  
            .then(response => {
                if (!response.ok) throw new Error('Failed to load footer');
                return response.text();
            })
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
                console.log('Footer loaded successfully');
                initializeFooterInteractions();
            })
            .catch(error => {
                console.error('Error loading footer:', error);
                document.getElementById('footer-container').innerHTML = '<div>Footer failed to load</div>';
            });
        loadPromises.push(footerPromise);
        
        // Wait for all components to load
        Promise.all(loadPromises).then(() => {
            console.log('All components loaded');
            resolve();
        });
    });
}


function initializeFooterInteractions() {
    console.log("Initializing footer interactions...");
    
    const toggles = document.querySelectorAll(".footer-toggle");
    console.log(`Found ${toggles.length} footer toggles`);
    
    toggles.forEach(toggle => {

        toggle.replaceWith(toggle.cloneNode(true));
    });


    const newToggles = document.querySelectorAll(".footer-toggle");
    
    newToggles.forEach(toggle => {
        toggle.addEventListener("click", function() {
            console.log("Footer toggle clicked");
            const isExpanded = !this.classList.contains("active");
            this.classList.toggle("active", isExpanded);
            this.setAttribute("aria-expanded", isExpanded);
            
            const panel = this.nextElementSibling;
            if (panel) {
                if (isExpanded) {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } else {
                    panel.style.maxHeight = null;
                }
            }
        });
    });
}

// Sidebar Interactions
function initializeSidebarInteractions() {
    console.log("Initializing sidebar interactions...");

    // Add active state to sidebar links
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Mobile sidebar toggle (if needed)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
}

// Header Interactions
function initializeHeaderInteractions() {
    console.log("Initializing header interactions...");

    // Notification icon click effect
    const notificationIcon = document.getElementById('notificationIcon');
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            // Add notification dropdown logic here
        });
    }

    // Settings icon click effect
    const settingsIcon = document.getElementById('settingsIcon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            alert('Settings panel would open here!');
        });
    }

    // User menu dropdown
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        userMenu.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    }
}

// Theme System with integrated auto theme
function initializeThemeSystem() {
    console.log("Initializing theme system...");

    // Theme selector functionality
    const themeToggle = document.getElementById('themeToggle');
    const themeSelector = document.getElementById('themeSelector');
    const themeOptions = document.querySelectorAll('.theme-option');
    const autoThemeSection = document.getElementById('autoThemeSection');

    const themes = {
        1: { primary: '#3498db', secondary: '#2980b9' },
        2: { primary: '#0984e3', secondary: '#6c5ce7' },
        3: { primary: '#00cec9', secondary: '#00b894' },
        4: { primary: '#74b9ff', secondary: '#a29bfe' },
        5: { primary: '#487eb0', secondary: '#40739e' }
    };

    let autoThemeInterval;
    let isAutoThemeActive = false;

    // Toggle theme selector
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            themeSelector.classList.toggle('active');
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }

    // Apply theme function
    function applyTheme(themeId) {
        const theme = themes[themeId];
        if (!theme) return;

        document.documentElement.style.setProperty('--primary', theme.primary);
        document.documentElement.style.setProperty('--secondary', theme.secondary);

        // Update active theme indicator
        if (themeOptions) {
            themeOptions.forEach(option => {
                option.classList.remove('active');
                if (option.dataset.theme == themeId) {
                    option.classList.add('active');
                }
            });
        }

        // Save theme preference
        localStorage.setItem('crowEducationTheme', themeId);
        console.log('Applied theme:', themeId);
    }

    // Theme option click handlers
    if (themeOptions) {
        themeOptions.forEach(option => {
            option.addEventListener('click', function () {
                const themeId = this.dataset.theme;
                applyTheme(themeId);

                // Stop auto theme if manually selecting a theme
                if (isAutoThemeActive) {
                    toggleAutoTheme();
                }
            });
        });
    }

    // Auto theme functionality
    function toggleAutoTheme() {
        isAutoThemeActive = !isAutoThemeActive;
        if (autoThemeSection) {
            autoThemeSection.classList.toggle('active', isAutoThemeActive);
        }

        if (isAutoThemeActive) {
            // Start auto theme cycling
            let currentTheme = 1;
            autoThemeInterval = setInterval(() => {
                currentTheme = currentTheme % 5 + 1;
                applyTheme(currentTheme);
            }, 3000);
        } else {
            // Stop auto theme cycling
            clearInterval(autoThemeInterval);
        }
        
        // Save auto theme state
        localStorage.setItem('crowEducationAutoTheme', isAutoThemeActive);
    }

    // Auto theme section click handler
    if (autoThemeSection) {
        autoThemeSection.addEventListener('click', toggleAutoTheme);
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('crowEducationTheme') || '1';
    applyTheme(savedTheme);

    // Load auto theme state
    const savedAutoTheme = localStorage.getItem('crowEducationAutoTheme');
    if (savedAutoTheme === 'true' && autoThemeSection) {
        toggleAutoTheme();
    }
}   

    // Music Player Functionality
    /* ---------------- Tracks - pointing to your /song/ folder ---------------- */
    const tracks = [
        { title: "GoodTime", artist: "Unknown Artist", src: "../song/song1.mp3", cover: "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=640&auto=format&fit=crop", duration: "3:45" },
        { title: "piano ", artist: "Unknown Artist", src: "../song/song2.mp3", cover: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=640&auto=format&fit=crop", duration: "4:12" },
        { title: "Motivation", artist: "Unknown Artist", src: "../song/song3.mp3", cover: "https://images.unsplash.com/photo-1511376777868-611b54f68947?q=80&w=640&auto=format&fit=crop", duration: "5:02" }
    ];

    // Playlist functionality
    const PLAYLIST_KEY = 'crow_music_playlists';
    let playlists = JSON.parse(localStorage.getItem(PLAYLIST_KEY) || '[]');
    let currentPlaylistId = null;

    // Initialize default playlists if none exist
    if (playlists.length === 0) {
        playlists = [
            {
                id: 'focus',
                name: 'Focus Playlist',
                description: 'A collection of focus-enhancing tracks',
                cover: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=640&auto=format&fit=crop',
                tracks: [tracks[0].src, tracks[1].src],
                createdAt: new Date().toISOString()
            },
            {
                id: 'chill',
                name: 'Chill Vibes',
                description: 'Relaxing music for study breaks',
                cover: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=640&auto=format&fit=crop',
                tracks: [tracks[2].src],
                createdAt: new Date().toISOString()
            }
        ];
        savePlaylists();
    }

    // Save playlists to localStorage
    function savePlaylists() {
        localStorage.setItem(PLAYLIST_KEY, JSON.stringify(playlists));
    }

    // Render playlists in the sidebar
    function renderPlaylists() {
        const playlistsContainer = document.getElementById('playlistsContainer');
        playlistsContainer.innerHTML = '';

        playlists.forEach(playlist => {
            const playlistCard = document.createElement('div');
            playlistCard.className = `playlist-card ${currentPlaylistId === playlist.id ? 'active' : ''}`;
            playlistCard.dataset.playlistId = playlist.id;

            // Calculate total duration for the playlist
            const playlistTracks = tracks.filter(track => playlist.tracks.includes(track.src));
            const totalDuration = playlistTracks.reduce((total, track) => {
                const [min, sec] = track.duration.split(':').map(Number);
                return total + min * 60 + sec;
            }, 0);

            const minutes = Math.floor(totalDuration / 60);
            const seconds = totalDuration % 60;
            const formattedDuration = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            playlistCard.innerHTML = `
                <img src="${playlist.cover}" alt="cover">
                <div class="playlist-meta">
                    <div class="name">${playlist.name}</div>
                    <div class="sub">${playlist.tracks.length} songs • ${formattedDuration}</div>
                </div>
            `;

            playlistCard.addEventListener('click', () => {
                selectPlaylist(playlist.id);
            });

            playlistsContainer.appendChild(playlistCard);
        });
    }

    // Select a playlist
    function selectPlaylist(playlistId) {
        currentPlaylistId = playlistId;
        renderPlaylists();

        const playlist = playlists.find(p => p.id === playlistId);
        if (playlist) {
            document.getElementById('currentPlaylistName').textContent = playlist.name;
            document.getElementById('currentPlaylistDescription').textContent = playlist.description;
            renderCurrentTab();
        }
    }

    // DOM elems
    const tracksBody = document.getElementById('tracksBody');
    const audio = document.getElementById('audio');
    const playerCover = document.getElementById('playerCover');
    const playerTitle = document.getElementById('playerTitle');
    const playerArtist = document.getElementById('playerArtist');
    const playPauseMain = document.getElementById('playPauseMain');
    const prevTrackBtn = document.getElementById('prevTrack');
    const nextTrackBtn = document.getElementById('nextTrack');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const currentTimeEl = document.getElementById('currentTime');
    const totalTimeEl = document.getElementById('totalTime');
    const volumeRange = document.getElementById('volumeRange');
    const searchInput = document.getElementById('searchInput');
    const tabAll = document.getElementById('tabAll');
    const tabFavs = document.getElementById('tabFavs');
    const loopBtn = document.getElementById('loopBtn');

    let currentIndex = -1;
    let isPlaying = false;
    let isLooping = false;

    // favorites (store src strings)
    const FAV_KEY = 'crow_music_favs';
    let favs = JSON.parse(localStorage.getItem(FAV_KEY) || '[]');

    // helper: is favorite
    function isFav(src) {
        return favs.includes(src);
    }

    // save favorites
    function saveFavs() {
        localStorage.setItem(FAV_KEY, JSON.stringify(favs));
    }

    // Get tracks for current playlist
    function getCurrentPlaylistTracks() {
        if (!currentPlaylistId) return [];

        const playlist = playlists.find(p => p.id === currentPlaylistId);
        if (!playlist) return [];

        return tracks.filter(track => playlist.tracks.includes(track.src));
    }

    // render tracks for either "all" or "favorites" view, optionally filtered
    function renderTracksFor(list) {
        tracksBody.innerHTML = '';
        list.forEach((t, displayIndex) => {
            // find original index for this track (by src)
            const origIndex = tracks.findIndex(item => item.src === t.src);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="width:40px;"><div class="track-index">${displayIndex + 1}</div></td>
                <td>
                    <div class="track-row">
                        <div class="track-title">
                            <img src="${t.cover}" alt="cover">
                            <div>
                                <div class="track-name">${t.title}</div>
                                <div class="track-artist">${t.artist}</div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="padding-left:10px; color:var(--gray)">${t.artist}</td>
                <td style="text-align:right; padding-right:12px;">
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">
                        <button class="fav-btn ${isFav(t.src) ? 'active' : ''}" data-src="${t.src}" title="Toggle Favorite">
                            <i class="fas fa-heart"></i>
                        </button>
                        <span class="track-duration">${t.duration}</span>
                    </div>
                </td>
            `;
            // clicking row plays (use origIndex)
            tr.addEventListener('click', (e) => {
                // if click target is favorite button, ignore row click
                if (e.target.closest('.fav-btn')) return;
                playTrackAt(origIndex);
            });
            // favorite button
            const favBtn = tr.querySelector('.fav-btn');
            favBtn.addEventListener('click', (ev) => {
                ev.stopPropagation();
                const src = favBtn.getAttribute('data-src');
                if (isFav(src)) {
                    // remove
                    favs = favs.filter(s => s !== src);
                    favBtn.classList.remove('active');
                } else {
                    favs.push(src);
                    favBtn.classList.add('active');
                }
                saveFavs();
                // if we're in Favorites tab and removed, re-render tab
                if (tabFavs.classList.contains('active')) {
                    renderCurrentTab();
                }
            });

            tracksBody.appendChild(tr);
        });
        // highlight active row if visible and matches
        Array.from(tracksBody.children).forEach((r, i) => {
            const t = list[i];
            const orig = tracks.findIndex(tt => tt.src === t.src);
            r.classList.toggle('active-row', orig === currentIndex);
        });
    }

    // current tab state
    let currentTab = 'all'; // 'all' or 'favs'

    function renderCurrentTab() {
        const q = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const playlistTracks = getCurrentPlaylistTracks();

        if (currentTab === 'all') {
            let list = playlistTracks.slice();
            if (q) list = list.filter(t => t.title.toLowerCase().includes(q) || t.artist.toLowerCase().includes(q));
            renderTracksFor(list);
        } else {
            // favorites: find tracks whose src in favs, maintain order of tracks array
            let list = playlistTracks.filter(t => favs.includes(t.src));
            if (q) list = list.filter(t => t.title.toLowerCase().includes(q) || t.artist.toLowerCase().includes(q));
            renderTracksFor(list);
        }
    }

    // play given index (index in original tracks array)
    function playTrackAt(index) {
        if (index < 0 || index >= tracks.length) return;
        currentIndex = index;
        const t = tracks[index];
        audio.src = t.src;
        playerCover.src = t.cover;
        playerTitle.textContent = t.title;
        playerArtist.textContent = t.artist;
        // update highlighted row if visible
        renderCurrentTab();

        audio.play().then(() => {
            isPlaying = true;
            playPauseMain.innerHTML = '<i class="fas fa-pause"></i>';
        }).catch(err => {
            isPlaying = false;
            playPauseMain.innerHTML = '<i class="fas fa-play"></i>';
        });
    }

    // play/pause main control
    playPauseMain.addEventListener('click', () => {
        if (!audio.src) {
            // If no track is selected, play the first track of the current playlist
            const playlistTracks = getCurrentPlaylistTracks();
            if (playlistTracks.length > 0) {
                const firstTrack = playlistTracks[0];
                const origIndex = tracks.findIndex(t => t.src === firstTrack.src);
                playTrackAt(origIndex);
            }
            return;
        }
        if (audio.paused) {
            audio.play();
            playPauseMain.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            audio.pause();
            playPauseMain.innerHTML = '<i class="fas fa-play"></i>';
        }
    });

    // prev/next
    prevTrackBtn.addEventListener('click', () => {
        if (currentIndex <= 0) playTrackAt(tracks.length - 1);
        else playTrackAt(currentIndex - 1);
    });
    nextTrackBtn.addEventListener('click', () => {
        if (currentIndex === -1 || currentIndex >= tracks.length - 1) playTrackAt(0);
        else playTrackAt(currentIndex + 1);
    });

    // loop button toggle
    loopBtn.addEventListener('click', () => {
        isLooping = !isLooping;
        loopBtn.classList.toggle('active', isLooping);
        // visually rotate icon when active
        loopBtn.title = isLooping ? 'Loop: ON' : 'Loop: OFF';
    });

    // update progress
    audio.addEventListener('timeupdate', () => {
        if (!audio.duration) return;
        const pct = (audio.currentTime / audio.duration) * 100;
        progressFill.style.width = pct + '%';
        currentTimeEl.textContent = formatTime(audio.currentTime);
        totalTimeEl.textContent = formatTime(audio.duration);
    });

    // click progress to seek
    progressBar.addEventListener('click', (e) => {
        if (!audio.duration) return;
        const rect = progressBar.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const pct = x / rect.width;
        audio.currentTime = pct * audio.duration;
    });

    // format time mm:ss
    function formatTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    // volume
    volumeRange.addEventListener('input', () => {
        audio.volume = volumeRange.value;
    });

    // when ended -> if looping play same, else next
    audio.addEventListener('ended', () => {
        if (isLooping && currentIndex !== -1) {
            // restart current track
            audio.currentTime = 0;
            audio.play();
        } else {
            nextTrackBtn.click();
        }
    });

    // search filter
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            renderCurrentTab();
        });
    }

    // tabs
    tabAll.addEventListener('click', () => {
        currentTab = 'all';
        tabAll.classList.add('active');
        tabFavs.classList.remove('active');
        renderCurrentTab();
    });
    tabFavs.addEventListener('click', () => {
        currentTab = 'favs';
        tabFavs.classList.add('active');
        tabAll.classList.remove('active');
        renderCurrentTab();
    });

    // keyboard: space to play/pause
    document.addEventListener('keydown', (e) => {
        if (e.code === 'Space' && document.activeElement.tagName !== 'INPUT') {
            e.preventDefault();
            playPauseMain.click();
        }
    });

    // set default volume
    audio.volume = parseFloat(volumeRange.value);

    // Initialize the app
    function initApp() {
        renderPlaylists();
        // Select the first playlist by default
        if (playlists.length > 0 && !currentPlaylistId) {
            selectPlaylist(playlists[0].id);
        }
    }

    initApp();
