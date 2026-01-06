document.addEventListener('DOMContentLoaded', function () {

            let currentPage = 'dashboard';
            const pageTitles = {
                'dashboard': 'Teacher Dashboard',
                'classes': 'Manage Users', 
                'homework': 'Manage Homeworks',
                'assessment': 'Manage Assessments',
                'calendar': 'Manage Calendar',
                'messages': 'Manage Messages',
                'music': 'Manage Music',
                'settings': 'Manage Settings'
            };

            const mainFooter = document.getElementById('main-footer');
            const pageContentWrappers = document.querySelectorAll('.page-content');
            const dashboardPage = document.getElementById('dashboard-page');

            function switchPage(page) {
                const pageId = `${page}-page`;
                const targetPage = document.getElementById(pageId);

                if (!targetPage || !pageTitles[page]) {
                    console.warn(`Page not found: ${page}`);
                    return;
                }

                document.getElementById('pageTitle').textContent = pageTitles[page];

                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                });
                const activeLink = document.querySelector(`.menu-link[data-page="${page}"]`);
                if (activeLink) {
                    activeLink.closest('.menu-item').classList.add('active');
                }

                pageContentWrappers.forEach(wrapper => {
                    wrapper.style.display = 'none';
                });

                targetPage.style.display = 'flex';

                if (page === 'music') {
                    mainFooter.style.display = 'none';
                } else {
                    mainFooter.style.display = 'block';
                }

                currentPage = page;
            }

            document.querySelectorAll('.menu-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    switchPage(page);
                });
            });

            const darkModeToggle = document.getElementById('darkModeToggle');
            const toggleIcon = darkModeToggle.querySelector('.toggle-icon');
            const toggleText = darkModeToggle.querySelector('.toggle-text');

            const isDarkMode = localStorage.getItem('darkMode') === 'true';

            if (isDarkMode) {
                document.body.classList.add('dark-mode');
                toggleIcon.classList.remove('fa-moon');
                toggleIcon.classList.add('fa-sun');
                toggleText.textContent = 'Light Mode';
            }

            darkModeToggle.addEventListener('click', function () {
                document.body.classList.toggle('dark-mode');

                if (document.body.classList.contains('dark-mode')) {
                    toggleIcon.classList.remove('fa-moon');
                    toggleIcon.classList.add('fa-sun');
                    toggleText.textContent = 'Light Mode';
                    localStorage.setItem('darkMode', 'true');
                } else {
                    toggleIcon.classList.remove('fa-sun');
                    toggleIcon.classList.add('fa-moon');
                    toggleText.textContent = 'Dark Mode';
                    localStorage.setItem('darkMode', 'false');
                }
            });

            const notificationIcon = document.getElementById('notificationIcon');
            const notificationPanel = document.getElementById('notificationPanel');

            notificationIcon.addEventListener('click', function (e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('active');
                userMenu.classList.remove('active');

                this.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    this.classList.remove('animate__animated', 'animate__shakeX');
                }, 500);
            });

            const userAvatar = document.getElementById('userAvatar');
            const userMenu = document.getElementById('userMenu');

            userAvatar.addEventListener('click', function (e) {
                e.stopPropagation();
                userMenu.classList.toggle('active');
                notificationPanel.classList.remove('active');
            });

            document.addEventListener('click', function () {
                notificationPanel.classList.remove('active');
                userMenu.classList.remove('active');
            });

            document.getElementById('profileBtn').addEventListener('click', function () {
                alert('Profile page would open here');
                userMenu.classList.remove('active');
            });

            document.getElementById('settingsBtn').addEventListener('click', function () {
                switchPage('settings'); 
                userMenu.classList.remove('active');
            });

            document.getElementById('logoutBtn').addEventListener('click', function () {
                if (confirm('Are you sure you want to logout?')) {
                    alert('Logging out...');
                }
                userMenu.classList.remove('active');
            });

            const globalSearch = document.getElementById('globalSearch');
            globalSearch.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                if (searchTerm) {
                    console.log(`Searching for: ${searchTerm}`);
                }
            });

            if (document.getElementById('music-page')) {
                const tracks = [
                    { title: "GoodTime", artist: "Unknown Artist", src: "../song/song1.mp3", cover: "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=640&auto=format&fit=crop", duration: "3:45" },
                    { title: "Piano Melody", artist: "Unknown Artist", src: "../song/song2.mp3", cover: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=640&auto=format&fit=crop", duration: "4:12" },
                    { title: "Motivation", artist: "Unknown Artist", src: "../song/song3.mp3", cover: "https://images.unsplash.com/photo-1511376777868-611b54f68947?q=80&w=640&auto=format&fit=crop", duration: "5:02" }
                ];

                const PLAYLIST_KEY = 'crow_music_playlists';
                let playlists = JSON.parse(localStorage.getItem(PLAYLIST_KEY) || '[]');
                let currentPlaylistId = null;

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

                function savePlaylists() {
                    localStorage.setItem(PLAYLIST_KEY, JSON.stringify(playlists));
                }

                function renderPlaylists() {
                    const playlistsContainer = document.getElementById('playlistsContainer');
                    if (!playlistsContainer) return; 
                    playlistsContainer.innerHTML = '';

                    playlists.forEach(playlist => {
                        const playlistCard = document.createElement('div');

                        playlistCard.className = `playlist-card ${currentPlaylistId === playlist.id ? 'active' : ''}`;
                        playlistCard.dataset.playlistId = playlist.id;

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

                const FAV_KEY = 'crow_music_favs';
                let favs = JSON.parse(localStorage.getItem(FAV_KEY) || '[]');

                function isFav(src) {
                    return favs.includes(src);
                }

                function saveFavs() {
                    localStorage.setItem(FAV_KEY, JSON.stringify(favs));
                }

                function getCurrentPlaylistTracks() {
                    if (!currentPlaylistId) return [];

                    const playlist = playlists.find(p => p.id === currentPlaylistId);
                    if (!playlist) return [];

                    return tracks.filter(track => playlist.tracks.includes(track.src));
                }

                function renderTracksFor(list) {
                    if (!tracksBody) return; 
                    tracksBody.innerHTML = '';
                    list.forEach((t, displayIndex) => {
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
                        tr.addEventListener('click', (e) => {
                            if (e.target.closest('.fav-btn')) return;
                            playTrackAt(origIndex);
                        });
                        const favBtn = tr.querySelector('.fav-btn');
                        favBtn.addEventListener('click', (ev) => {
                            ev.stopPropagation();
                            const src = favBtn.getAttribute('data-src');
                            if (isFav(src)) {
                                favs = favs.filter(s => s !== src);
                                favBtn.classList.remove('active');
                            } else {
                                favs.push(src);
                                favBtn.classList.add('active');
                            }
                            saveFavs();
                            if (tabFavs.classList.contains('active')) {
                                renderCurrentTab();
                            }
                        });

                        tracksBody.appendChild(tr);
                    });
                    Array.from(tracksBody.children).forEach((r, i) => {
                        const t = list[i];
                        const orig = tracks.findIndex(tt => tt.src === t.src);
                        r.classList.toggle('active-row', orig === currentIndex);
                    });
                }

                let currentTab = 'all'; 

                function getCurrentViewTracks() {
                    const q = searchInput ? searchInput.value.trim().toLowerCase() : '';
                    const playlistTracks = getCurrentPlaylistTracks();
                    let list;

                    if (currentTab === 'all') {
                        list = playlistTracks.slice();
                    } else { 
                        list = playlistTracks.filter(t => favs.includes(t.src));
                    }

                    if (q) {
                        list = list.filter(t => t.title.toLowerCase().includes(q) || t.artist.toLowerCase().includes(q));
                    }
                    return list;
                }

                function renderCurrentTab() {
                    const list = getCurrentViewTracks();
                    renderTracksFor(list);
                }

                function playTrackAt(index) {
                    if (index < 0 || index >= tracks.length) return;
                    currentIndex = index;
                    const t = tracks[index];
                    audio.src = t.src;
                    playerCover.src = t.cover;
                    playerTitle.textContent = t.title;
                    playerArtist.textContent = t.artist;
                    renderCurrentTab();

                    audio.play().then(() => {
                        isPlaying = true;
                        playPauseMain.innerHTML = '<i class="fas fa-pause"></i>';
                    }).catch(err => {
                        isPlaying = false;
                        playPauseMain.innerHTML = '<i class="fas fa-play"></i>';
                    });
                }

                playPauseMain.addEventListener('click', () => {
                    if (!audio.src) {
                        const tracksToPlay = getCurrentViewTracks();
                        if (tracksToPlay.length > 0) {
                            const firstTrack = tracksToPlay[0];
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

                prevTrackBtn.addEventListener('click', () => {
                    const currentViewTracks = getCurrentViewTracks();
                    if (currentViewTracks.length === 0) return; 

                    const currentTrack = tracks[currentIndex];
                    let indexInView = currentViewTracks.findIndex(t => t.src === (currentTrack ? currentTrack.src : ''));

                    let prevIndexInView = indexInView - 1;
                    if (indexInView === -1 || prevIndexInView < 0) {
                        prevIndexInView = currentViewTracks.length - 1;
                    }

                    const prevTrack = currentViewTracks[prevIndexInView];
                    const originalIndex = tracks.findIndex(t => t.src === prevTrack.src);
                    playTrackAt(originalIndex);
                });

                nextTrackBtn.addEventListener('click', () => {
                    const currentViewTracks = getCurrentViewTracks();
                        if (currentViewTracks.length === 0) return;

                    const currentTrack = tracks[currentIndex];
                    let indexInview = currentViewTracks.findIndex(t => t.src === (currentTrack ? currentTrack.src : ""));

                    let nextIndexInview = indexInview + 1;
                        if (indexInview === -1 || nextIndexInview >= currentViewTracks.length) {
                        nextIndexInview = 0;
                    }

                    const nextTrack = currentViewTracks[nextIndexInview];
                    const originalIndex = tracks.findIndex(t => t.src === nextTrack.src);
                        playTrackAt(originalIndex);
                });

                loopBtn.addEventListener('click', () => {
                    isLooping = !isLooping;
                    loopBtn.classList.toggle('active', isLooping);
                    loopBtn.title = isLooping ? 'Loop: ON' : 'Loop: OFF';
                });

                audio.addEventListener('timeupdate', () => {
                    if (!audio.duration) return;
                    const pct = (audio.currentTime / audio.duration) * 100;
                    progressFill.style.width = pct + '%';
                    currentTimeEl.textContent = formatTime(audio.currentTime);
                    totalTimeEl.textContent = formatTime(audio.duration);
                });

                progressBar.addEventListener('click', (e) => {
                    if (!audio.duration) return;
                    const rect = progressBar.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const pct = x / rect.width;
                    audio.currentTime = pct * audio.duration;
                });

                function formatTime(sec) {
                    if (!sec || isNaN(sec)) return '0:00';
                    const m = Math.floor(sec / 60);
                    const s = Math.floor(sec % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                }

                volumeRange.addEventListener('input', () => {
                    audio.volume = volumeRange.value;
                });

                audio.addEventListener('ended', () => {
                    if (isLooping && currentIndex !== -1) {
                        audio.currentTime = 0;
                        audio.play();
                    } else {
                        nextTrackBtn.click();
                    }
                });

                if (searchInput) {
                    searchInput.addEventListener('input', () => {
                        renderCurrentTab();
                    });
                }

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

                document.addEventListener('keydown', (e) => {
                    const activeEl = document.activeElement;
                    if (e.code === 'Space' && activeEl.tagName !== 'INPUT' && activeEl.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                        playPauseMain.click();
                    }
                });

                audio.volume = parseFloat(volumeRange.value);

                function initApp() {
                    renderPlaylists();
                    if (playlists.length > 0 && !currentPlaylistId) {
                        selectPlaylist(playlists[0].id);
                    }
                }

                initApp();

                const uploadBtn = document.getElementById('uploadBtn');
                const fileInput = document.getElementById('fileInput');

                if (uploadBtn) {
                    uploadBtn.addEventListener('click', function () {
                        fileInput.click();
                    });
                }

                const addBtn = document.getElementById('addBtn');
                if (addBtn) {
                    addBtn.addEventListener('click', function () {
                        fileInput.click();
                    });
                }
            } 

            const toggles = document.querySelectorAll(".footer-toggle");

            toggles.forEach(toggle => {
                toggle.addEventListener("click", () => {
                    const isExpanded = !toggle.classList.contains("active");
                    
                    toggle.classList.toggle("active", isExpanded);
                    
                    toggle.setAttribute("aria-expanded", isExpanded);
                    
                    const panel = toggle.nextElementSibling;
                    if (panel) { 
                        if (isExpanded) {
                            panel.style.maxHeight = panel.scrollHeight + "px";
                        } else {
                            panel.style.maxHeight = null;
                        }
                    }
                });
            });

        }); 