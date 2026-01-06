document.addEventListener('DOMContentLoaded', function () {
            
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
            
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function () {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const contactsList = document.getElementById('contactsList');
            const chatHeader = document.getElementById('chatHeader');
            const chatMessages = document.getElementById('chatMessages');
            const emptyChat = document.getElementById('emptyChat');
            const chatInput = document.getElementById('chatInput');
            const messageInput = document.getElementById('messageInput');
            const sendBtn = document.getElementById('sendBtn');
            const chatAvatar = document.getElementById('chatAvatar');
            const chatName = document.getElementById('chatName');
            const chatStatus = document.getElementById('chatStatus');
            const contactsSearch = document.getElementById('contactsSearch');
            const globalSearch = document.getElementById('globalSearch');
            const newChatBtn = document.getElementById('newChatBtn');
            const deleteStudentBtn = document.getElementById('deleteStudentBtn');
            const contactInfoBtn = document.getElementById('contactInfoBtn');
            const attachmentBtn = document.getElementById('attachmentBtn');
            const addStudentBtn = document.getElementById('addStudentBtn');

            const newChatModal = document.getElementById('newChatModal');
            const contactInfoModal = document.getElementById('contactInfoModal');
            const addStudentModal = document.getElementById('addStudentModal');
            const attachmentModal = document.getElementById('attachmentModal');
            const closeNewChatModal = document.getElementById('closeNewChatModal');
            const cancelNewChatBtn = document.getElementById('cancelNewChatBtn');
            const startChatBtn = document.getElementById('startChatBtn');
            const modalContactsList = document.getElementById('modalContactsList');
            const modalSearch = document.getElementById('modalSearch');
            const closeContactInfoModal = document.getElementById('closeContactInfoModal');
            const closeContactInfoBtn = document.getElementById('closeContactInfoBtn');
            const infoAvatar = document.getElementById('infoAvatar');
            const infoName = document.getElementById('infoName');
            const infoStatus = document.getElementById('infoStatus');
            const infoEmail = document.getElementById('infoEmail');
            const infoPhone = document.getElementById('infoPhone');
            const closeAddStudentModal = document.getElementById('closeAddStudentModal');
            const cancelAddStudentBtn = document.getElementById('cancelAddStudentBtn');
            const saveStudentBtn = document.getElementById('saveStudentBtn');
            const closeAttachmentModal = document.getElementById('closeAttachmentModal');
            const attachDocument = document.getElementById('attachDocument');
            const attachImage = document.getElementById('attachImage');
            const attachVideo = document.getElementById('attachVideo');
            const attachAudio = document.getElementById('attachAudio');
            const fileInput = document.getElementById('fileInput');

            const contacts = [
                {
                    id: 1,
                    name: 'John Doe',
                    avatar: 'JD',
                    status: 'Online',
                    lastMessage: 'Thanks for the help with the assignment!',
                    time: '10:30 AM',
                    unread: 2,
                    email: 'john.doe@example.com',
                    phone: '+1 (555) 123-4567',
                    messages: [
                        { id: 1, text: 'Hi there! I need help with my math homework.', time: '10:15 AM', sent: false },
                        { id: 2, text: 'Sure, which problem are you stuck on?', time: '10:16 AM', sent: true },
                        { id: 3, text: 'Problem 5 in chapter 3. The quadratic equation.', time: '10:20 AM', sent: false },
                        { id: 4, text: 'I can help with that. Let me send you the solution.', time: '10:25 AM', sent: true },
                        { id: 5, text: 'Thanks for the help with the assignment!', time: '10:30 AM', sent: false }
                    ]
                },
                {
                    id: 2,
                    name: 'Sarah Johnson',
                    avatar: 'SJ',
                    status: 'Offline',
                    lastMessage: 'When is the next parent-teacher meeting?',
                    time: 'Yesterday',
                    unread: 0,
                    email: 'sarah.johnson@example.com',
                    phone: '+1 (555) 234-5678',
                    messages: [
                        { id: 1, text: 'Hello Ms. Yee, when is the next parent-teacher meeting?', time: 'Yesterday', sent: false },
                        { id: 2, text: 'It\'s scheduled for next Friday at 3 PM.', time: 'Yesterday', sent: true }
                    ]
                },
                {
                    id: 3,
                    name: 'Michael Chen',
                    avatar: 'MC',
                    status: 'Online',
                    lastMessage: 'Can I get an extension for the essay?',
                    time: '2 hours ago',
                    unread: 1,
                    email: 'michael.chen@example.com',
                    phone: '+1 (555) 345-6789',
                    messages: [
                        { id: 1, text: 'Hi Ms. Yee, I\'m having trouble finishing my essay on time.', time: '2 hours ago', sent: false },
                        { id: 2, text: 'Can I get an extension until Monday?', time: '2 hours ago', sent: false }
                    ]
                },
                {
                    id: 4,
                    name: 'Emily Wilson',
                    avatar: 'EW',
                    status: 'Away',
                    lastMessage: 'I enjoyed the science lesson today!',
                    time: '5 hours ago',
                    unread: 0,
                    email: 'emily.wilson@example.com',
                    phone: '+1 (555) 456-7890',
                    messages: [
                        { id: 1, text: 'Hi Ms. Yee, I really enjoyed the science lesson today!', time: '5 hours ago', sent: false },
                        { id: 2, text: 'The experiment was really interesting.', time: '5 hours ago', sent: false },
                        { id: 3, text: 'I\'m glad you enjoyed it! We\'ll do more experiments next week.', time: '4 hours ago', sent: true }
                    ]
                }
            ];

            let currentContact = null;
            let selectedContactForNewChat = null;

            function formatTime(date) {
                let hours = date.getHours();
                let minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; 
                minutes = minutes < 10 ? '0' + minutes : minutes;
                return `${hours}:${minutes} ${ampm}`;
            }

            function initContacts() {
                contactsList.innerHTML = '';
                
                contacts.forEach(contact => {
                    const contactItem = document.createElement('div');
                    contactItem.className = 'contact-item';
                    contactItem.dataset.id = contact.id;
                    
                    contactItem.innerHTML = `
                        <div class="contact-avatar">${contact.avatar}</div>
                        <div class="contact-info">
                            <div class="contact-name">${contact.name}</div>
                            <div class="contact-last-message">${contact.lastMessage}</div>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-time">${contact.time}</div>
                            ${contact.unread > 0 ? `<div class="contact-unread">${contact.unread}</div>` : ''}
                        </div>
                    `;
                    
                    contactItem.addEventListener('click', () => selectContact(contact));
                    contactsList.appendChild(contactItem);
                });
            }

            function initModalContacts() {
                modalContactsList.innerHTML = '';
                
                contacts.forEach(contact => {
                    const contactItem = document.createElement('div');
                    contactItem.className = 'contact-select-item';
                    contactItem.dataset.id = contact.id;
                    
                    contactItem.innerHTML = `
                        <div class="contact-avatar">${contact.avatar}</div>
                        <div class="contact-info">
                            <div class="contact-name">${contact.name}</div>
                            <div class="contact-last-message">${contact.email}</div>
                        </div>
                    `;
                    
                    contactItem.addEventListener('click', () => {
                        
                        document.querySelectorAll('.contact-select-item').forEach(item => {
                            item.classList.remove('selected');
                        });
                        
                        
                        contactItem.classList.add('selected');
                        selectedContactForNewChat = contact;
                    });
                    
                    modalContactsList.appendChild(contactItem);
                });
            }

        
            function selectContact(contact) {
                currentContact = contact;
                
                document.querySelectorAll('.contact-item').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector(`.contact-item[data-id="${contact.id}"]`).classList.add('active');
                
                chatHeader.style.display = 'flex';
                emptyChat.style.display = 'none';
                chatInput.style.display = 'flex';
                
                chatAvatar.textContent = contact.avatar;
                chatName.textContent = contact.name;
                chatStatus.textContent = contact.status;
                
                loadMessages(contact.messages);
                
                contact.unread = 0;
                initContacts(); 
            }

            function loadMessages(messages) {
                chatMessages.innerHTML = '';
                
                messages.forEach(message => {
                    if (message.type === 'attachment') {
                        addAttachmentToChat(message.attachment, message.time, message.sent);
                    } else {
                        addMessageToChat(message.text, message.time, message.sent);
                    }
                });
    
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function addMessageToChat(text, time, isSent) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
                
                const now = new Date();
                const messageTime = time || formatTime(now);
                
                messageDiv.innerHTML = `
                    <div class="message-avatar">${isSent ? 'YC' : (currentContact ? currentContact.avatar : '')}</div>
                    <div class="message-content">
                        <div class="message-text">${text}</div>
                        <div class="message-time">${messageTime}</div>
                    </div>
                `;
                
                chatMessages.appendChild(messageDiv);
                
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function addAttachmentToChat(attachment, time, isSent) {
                const attachmentDiv = document.createElement('div');
                attachmentDiv.className = `attachment-message ${isSent ? 'sent' : 'received'}`;
                
                const now = new Date();
                const messageTime = time || formatTime(now);
                
                attachmentDiv.innerHTML = `
                    <div class="message-avatar">${isSent ? 'YC' : (currentContact ? currentContact.avatar : '')}</div>
                    <div class="attachment-content">
                        <div class="attachment-icon">
                            <i class="${attachment.icon}"></i>
                        </div>
                        <div class="attachment-info">
                            <div class="attachment-name">${attachment.name}</div>
                            <div class="attachment-size">${attachment.size}</div>
                        </div>
                    </div>
                    <div class="message-time">${messageTime}</div>
                `;
                
                chatMessages.appendChild(attachmentDiv);
                
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function sendMessage() {
                const text = messageInput.value.trim();
                if (!text || !currentContact) return;
                
                addMessageToChat(text, null, true);
                
                currentContact.lastMessage = text;
                const now = new Date();
                currentContact.time = formatTime(now);
                
                currentContact.messages.push({
                    id: currentContact.messages.length + 1,
                    text: text,
                    time: currentContact.time,
                    sent: true
                });
                
                messageInput.value = '';
                
                initContacts();
                
                setTimeout(() => {
                    const replies = [
                        "Thanks for your message!",
                        "I'll get back to you soon.",
                        "That's a good question.",
                        "Let me check on that for you.",
                        "I appreciate your feedback."
                    ];
                    const randomReply = replies[Math.floor(Math.random() * replies.length)];
                    
                    addMessageToChat(randomReply, null, false);
                    
                    currentContact.lastMessage = randomReply;
                    const replyTime = new Date();
                    currentContact.time = formatTime(replyTime);
                    
                    currentContact.messages.push({
                        id: currentContact.messages.length + 1,
                        text: randomReply,
                        time: currentContact.time,
                        sent: false
                    });
                    
                    initContacts();
                }, 1000 + Math.random() * 2000);
            }

            function deleteStudent() {
                if (!currentContact) {
                    alert('Please select a student to delete');
                    return;
                }
                
                if (confirm(`Are you sure you want to delete ${currentContact.name}? This action cannot be undone.`)) {
                
                    const contactIndex = contacts.findIndex(contact => contact.id === currentContact.id);
                    
                    if (contactIndex !== -1) {
    
                        contacts.splice(contactIndex, 1);
                        
                        let nextContact = null;
                        if (contacts.length > 0) {
                            if (contactIndex >= contacts.length) {
                                nextContact = contacts[contacts.length - 1];
                            } else {
                                nextContact = contacts[contactIndex];
                            }
                        }
                        
                        if (nextContact) {
                            selectContact(nextContact);
                        } else {
                            currentContact = null;
                            chatHeader.style.display = 'none';
                            chatInput.style.display = 'none';
                            emptyChat.style.display = 'flex';
                        }
                
                        initContacts();
                        
                        alert('Student has been deleted successfully!');
                    }
                }
            }

            function showContactInfo() {
                if (!currentContact) return;
                
                infoAvatar.textContent = currentContact.avatar;
                infoName.textContent = currentContact.name;
                infoStatus.textContent = currentContact.status;
                infoEmail.textContent = currentContact.email;
                infoPhone.textContent = currentContact.phone;
                
                contactInfoModal.style.display = 'flex';
            }

            function searchContacts(searchTerm) {
                const filteredContacts = contacts.filter(contact => 
                    contact.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    contact.lastMessage.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    contact.email.toLowerCase().includes(searchTerm.toLowerCase())
                );
                
                contactsList.innerHTML = '';
                
                if (filteredContacts.length === 0) {
                    contactsList.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--gray);">No contacts found</div>';
                    return;
                }
                
                filteredContacts.forEach(contact => {
                    const contactItem = document.createElement('div');
                    contactItem.className = 'contact-item';
                    contactItem.dataset.id = contact.id;
                    
                    contactItem.innerHTML = `
                        <div class="contact-avatar">${contact.avatar}</div>
                        <div class="contact-info">
                            <div class="contact-name">${contact.name}</div>
                            <div class="contact-last-message">${contact.lastMessage}</div>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-time">${contact.time}</div>
                            ${contact.unread > 0 ? `<div class="contact-unread">${contact.unread}</div>` : ''}
                        </div>
                    `;
                    
                    contactItem.addEventListener('click', () => selectContact(contact));
                    contactsList.appendChild(contactItem);
                });
            }

            function startNewChat() {
                if (!selectedContactForNewChat) {
                    alert('Please select a contact to start a chat');
                    return;
                }
                
                selectContact(selectedContactForNewChat);
                newChatModal.style.display = 'none';
                selectedContactForNewChat = null;
                
                document.querySelectorAll('.contact-select-item').forEach(item => {
                    item.classList.remove('selected');
                });
                modalSearch.value = '';
            }

            function addNewStudent() {
                const name = document.getElementById('studentName').value.trim();
                const email = document.getElementById('studentEmail').value.trim();
                const phone = document.getElementById('studentPhone').value.trim();
                
                if (!name || !email) {
                    alert('Please fill in all required fields: Name and Email');
                    return;
                }
                
                const newContact = {
                    id: contacts.length + 1,
                    name: name,
                    avatar: name.split(' ').map(n => n[0]).join('').toUpperCase(),
                    status: 'Online',
                    lastMessage: 'New student added',
                    time: formatTime(new Date()),
                    unread: 0,
                    email: email,
                    phone: phone,
                    messages: [
                        { id: 1, text: `Hello, I'm ${name}. I'm a new student.`, time: formatTime(new Date()), sent: false }
                    ]
                };
                
                contacts.push(newContact);
                
                initContacts();
                
                addStudentModal.style.display = 'none';
                
                document.getElementById('studentName').value = '';
                document.getElementById('studentEmail').value = '';
                document.getElementById('studentPhone').value = '';
            
                alert(`Student ${name} has been added successfully!`);
            }

            function attachFile(fileType) {
                if (!currentContact) {
                    alert('Please select a contact first');
                    return;
                }
                
                const fileTypes = {
                    'document': { icon: 'fas fa-file-pdf', name: 'Homework.pdf', size: '2.4 MB' },
                    'image': { icon: 'fas fa-image', name: 'Lesson_Image.jpg', size: '1.2 MB' },
                    'video': { icon: 'fas fa-video', name: 'Tutorial.mp4', size: '15.7 MB' },
                    'audio': { icon: 'fas fa-music', name: 'Lecture.mp3', size: '8.3 MB' }
                };
                
                const attachment = fileTypes[fileType];
                
                addAttachmentToChat(attachment, null, true);
                
                currentContact.lastMessage = `Sent a ${fileType}`;
                const now = new Date();
                currentContact.time = formatTime(now);
                
                currentContact.messages.push({
                    id: currentContact.messages.length + 1,
                    type: 'attachment',
                    attachment: attachment,
                    time: currentContact.time,
                    sent: true
                });
                
                initContacts();
                
                attachmentModal.style.display = 'none';
                
                setTimeout(() => {
                    const replies = [
                        "Thanks for the file!",
                        "I'll review this and get back to you.",
                        "This is very helpful, thank you!",
                        "I'll download this right away."
                    ];
                    const randomReply = replies[Math.floor(Math.random() * replies.length)];
                    
                    addMessageToChat(randomReply, null, false);
                    
                    currentContact.lastMessage = randomReply;
                    const replyTime = new Date();
                    currentContact.time = formatTime(replyTime);
                    
                    currentContact.messages.push({
                        id: currentContact.messages.length + 1,
                        text: randomReply,
                        time: currentContact.time,
                        sent: false
                    });
                    
                    initContacts();
                }, 1000 + Math.random() * 2000);
            }

            sendBtn.addEventListener('click', sendMessage);
            
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';

                this.style.overflow = 'hidden';
            });

            
            contactsSearch.addEventListener('input', function() {
                searchContacts(this.value);
            });
            
            globalSearch.addEventListener('input', function() {
                searchContacts(this.value);
            });
            
            modalSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const modalContacts = document.querySelectorAll('.contact-select-item');
                
                modalContacts.forEach(contact => {
                    const name = contact.querySelector('.contact-name').textContent.toLowerCase();
                    const email = contact.querySelector('.contact-last-message').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        contact.style.display = 'flex';
                    } else {
                        contact.style.display = 'none';
                    }
                });
            });

            newChatBtn.addEventListener('click', function() {
                newChatModal.style.display = 'flex';
                initModalContacts();
            });
            
            deleteStudentBtn.addEventListener('click', deleteStudent);
            
            contactInfoBtn.addEventListener('click', showContactInfo);
            
            attachmentBtn.addEventListener('click', function() {
                attachmentModal.style.display = 'flex';
            });
            
            addStudentBtn.addEventListener('click', function() {
                addStudentModal.style.display = 'flex';
            });

            closeNewChatModal.addEventListener('click', function() {
                newChatModal.style.display = 'none';
            });
            
            cancelNewChatBtn.addEventListener('click', function() {
                newChatModal.style.display = 'none';
            });
            
            startChatBtn.addEventListener('click', startNewChat);
            
            closeContactInfoModal.addEventListener('click', function() {
                contactInfoModal.style.display = 'none';
            });
            
            closeContactInfoBtn.addEventListener('click', function() {
                contactInfoModal.style.display = 'none';
            });
            
            closeAddStudentModal.addEventListener('click', function() {
                addStudentModal.style.display = 'none';
            });
            
            cancelAddStudentBtn.addEventListener('click', function() {
                addStudentModal.style.display = 'none';
            });
            
            saveStudentBtn.addEventListener('click', addNewStudent);
            
            closeAttachmentModal.addEventListener('click', function() {
                attachmentModal.style.display = 'none';
            });
            
            attachDocument.addEventListener('click', function() {
                attachFile('document');
            });
            
            attachImage.addEventListener('click', function() {
                attachFile('image');
            });
            
            attachVideo.addEventListener('click', function() {
                attachFile('video');
            });
            
            attachAudio.addEventListener('click', function() {
                attachFile('audio');
            });

            window.addEventListener('click', function(event) {
                if (event.target === newChatModal) {
                    newChatModal.style.display = 'none';
                }
                if (event.target === contactInfoModal) {
                    contactInfoModal.style.display = 'none';
                }
                if (event.target === addStudentModal) {
                    addStudentModal.style.display = 'none';
                }
                if (event.target === attachmentModal) {
                    attachmentModal.style.display = 'none';
                }
            });

            const footerToggles = document.querySelectorAll(".footer-toggle");
            
            footerToggles.forEach(toggle => {
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

            initContacts();
        });