const API_BASE_URL = 'http://localhost/hackathon'; // Replace with your actual API base URL

// Utility Functions
class APIError extends Error {
    constructor(message, statusCode) {
        super(message);
        this.name = 'APIError';
        this.statusCode = statusCode;
    }
}

// Generic API request handler
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    
    const config = {
        headers: {
            'Content-Type': 'application/json',
            ...options.headers
        },
        ...options
    };

    // Add authentication token if available
    const token = localStorage.getItem('authToken');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    try {
        const response = await fetch(url, config);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new APIError(
                errorData.message || `HTTP error! status: ${response.status}`,
                response.status
            );
        }
        
        return await response.json();
    } catch (error) {
        if (error instanceof APIError) {
            throw error;
        }
        throw new APIError(
            error.message || 'Network error occurred',
            0
        );
    }
}

// Authentication API Functions
const AuthAPI = {
    // Register a new user
    async register(userData) {
    return await apiRequest('/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(userData)
    });
    },

    // Login user
async login(credentials) {
    return await apiRequest('/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(credentials)
    });
},
    // Logout user
    async logout() {
        return await apiRequest('/auth/logout', {
            method: 'POST'
        });
    },

    // Request password reset
    async requestPasswordReset(email) {
        return await apiRequest('/auth/password/reset', {
            method: 'POST',
            body: JSON.stringify({ email })
        });
    },

    // Verify reset PIN
    async verifyResetPin(email, pin) {
        return await apiRequest('/auth/password/verify-pin', {
            method: 'POST',
            body: JSON.stringify({ email, pin })
        });
    },

    // Reset password with new password
    async resetPassword(email, newPassword, token) {
        return await apiRequest('/auth/password/update', {
            method: 'POST',
            body: JSON.stringify({ email, newPassword, token })
        });
    },

    // Get current user profile
    async getProfile() {
        return await apiRequest('/auth/profile');
    },

    // Update user profile
    async updateProfile(profileData) {
        return await apiRequest('/auth/profile', {
            method: 'PUT',
            body: JSON.stringify(profileData)
        });
    }
};

// Courses API Functions
const CoursesAPI = {
    // Get all courses
    async getAllCourses(filters = {}) {
        const queryParams = new URLSearchParams(filters).toString();
        return await apiRequest(`/courses?${queryParams}`);
    },

    // Get course by ID
    async getCourseById(courseId) {
        return await apiRequest(`/courses/${courseId}`);
    },

    // Enroll in a course
    async enrollInCourse(courseId) {
        return await apiRequest(`/courses/${courseId}/enroll`, {
            method: 'POST'
        });
    },

    // Get user's enrolled courses
    async getMyCourses() {
        return await apiRequest('/courses/my-courses');
    },

    // Get course progress
    async getCourseProgress(courseId) {
        return await apiRequest(`/courses/${courseId}/progress`);
    },

    // Update course progress
    async updateCourseProgress(courseId, progressData) {
        return await apiRequest(`/courses/${courseId}/progress`, {
            method: 'PUT',
            body: JSON.stringify(progressData)
        });
    },

    // Search courses
    async searchCourses(query, filters = {}) {
        const searchParams = new URLSearchParams({
            q: query,
            ...filters
        }).toString();
        return await apiRequest(`/courses/search?${searchParams}`);
    }
};

// Main API Object
const CrowEducationAPI = {
    auth: AuthAPI,
    courses: CoursesAPI,

    // Utility methods
    setAuthToken(token) {
        localStorage.setItem('authToken', token);
    },

    getAuthToken() {
        return localStorage.getItem('authToken');
    },

    removeAuthToken() {
        localStorage.removeItem('authToken');
    },

    isAuthenticated() {
        return !!this.getAuthToken();
    },

    // Handle API errors globally
    handleError(error, fallbackMessage = 'An error occurred') {
        console.error('API Error:', error);
        
        if (error instanceof APIError) {
            // Handle specific error codes
            switch (error.statusCode) {
                case 401:
                    // Unauthorized - redirect to login
                    this.removeAuthToken();
                    window.location.href = '../user/login.php';
                    break;
                case 403:
                    alert('You do not have permission to perform this action.');
                    break;
                case 404:
                    alert('The requested resource was not found.');
                    break;
                case 500:
                    alert('Server error. Please try again later.');
                    break;
                default:
                    alert(error.message || fallbackMessage);
            }
        } else {
            alert(fallbackMessage);
        }
        
        return error;
    }
};

// Mock/Development mode functions for testing without backend
const MockAPI = {
    // Mock user database
    users: [
        {
            id: 1,
            name: "Jason Tan",
            email: "jason@example.com",
            password: "Password123!",
            role: "student",
            createdAt: new Date().toISOString()
        },
        {
            id: 2,
            name: "Student User",
            email: "student@crow.edu",
            password: "Learn2023$",
            role: "student",
            createdAt: new Date().toISOString()
        },
        {
            id: 3,
            name: "Test User",
            email: "test@test.com",
            password: "Test123!",
            role: "teacher",
            createdAt: new Date().toISOString()
        }
    ],

    // Mock register function
    async register(userData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Check if email already exists
                const existingUser = this.users.find(user => user.email === userData.email);
                if (existingUser) {
                    reject(new APIError('Email already registered', 400));
                    return;
                }

                // Create new user
                const newUser = {
                    id: this.users.length + 1,
                    ...userData,
                    createdAt: new Date().toISOString()
                };
                this.users.push(newUser);

                resolve({
                    success: true,
                    message: 'Registration successful',
                    user: { id: newUser.id, name: newUser.name, email: newUser.email, role: newUser.role }
                });
            }, 1000);
        });
    },

    // Mock login function
    async login(credentials) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const user = this.users.find(u => 
                    u.email === credentials.email && u.password === credentials.password
                );

                if (user) {
                    const token = btoa(JSON.stringify({ userId: user.id, email: user.email }));
                    resolve({
                        success: true,
                        message: 'Login successful',
                        token,
                        user: { id: user.id, name: user.name, email: user.email, role: user.role }
                    });
                } else {
                    reject(new APIError('Invalid email or password', 401));
                }
            }, 1000);
        });
    },

    // Mock password reset request
    async requestPasswordReset(email) {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    success: true,
                    message: 'Password reset PIN sent to your email',
                    pin: '123456' // Mock PIN for development
                });
            }, 1000);
        });
    },

    // Mock verify reset PIN
    async verifyResetPin(email, pin) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (pin === '123456') {
                    resolve({
                        success: true,
                        message: 'PIN verified successfully',
                        resetToken: 'mock-reset-token'
                    });
                } else {
                    reject(new APIError('Invalid PIN', 400));
                }
            }, 1000);
        });
    },

    // Mock reset password
    async resetPassword(email, newPassword, token) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const user = this.users.find(u => u.email === email);
                if (user && token === 'mock-reset-token') {
                    user.password = newPassword;
                    resolve({
                        success: true,
                        message: 'Password reset successful'
                    });
                } else {
                    reject(new APIError('Password reset failed', 400));
                }
            }, 1000);
        });
    }
};

// Configuration
const IS_DEVELOPMENT = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

// Export appropriate API based on environment
if (false) {
    // Use mock API in development mode
    window.CrowEducationAPI = {
        ...CrowEducationAPI,
        auth: {
            ...CrowEducationAPI.auth,
            register: MockAPI.register,
            login: MockAPI.login,
            requestPasswordReset: MockAPI.requestPasswordReset,
            verifyResetPin: MockAPI.verifyResetPin,
            resetPassword: MockAPI.resetPassword
        }
    };
} else {
    // Use real API
    window.CrowEducationAPI = CrowEducationAPI;
}

// Global error handler for uncaught API errors
window.addEventListener('unhandledrejection', (event) => {
    if (event.reason instanceof APIError) {
        CrowEducationAPI.handleError(event.reason);
        event.preventDefault();
    }
});

// Auto-redirect to login if token expires
window.addEventListener('storage', (event) => {
    if (event.key === 'authToken' && event.oldValue && !event.newValue) {
        // Token was removed (likely expired)
        window.location.href = '../user/login.php';
    }
});