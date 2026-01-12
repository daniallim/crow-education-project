<div align="center">
  <a href="https://github.com/YOUR-USERNAME/crow-education">
    <img src="https://via.placeholder.com/150/0f172a/ffffff?text=Crow+Logo" alt="Logo" width="120" height="120">
  </a>

  <h1 align="center">Crow Education</h1>

  <p align="center">
    <b>The Modern Learning Ecosystem for SPM Students</b>
    <br />
    A hackathon initiative to democratize quality education in Malaysia.
  </p>

  <p align="center">
    <a href="#-project-overview">👀 Overview</a> •
    <a href="#-key-features">✨ Features</a> •
    <a href="#-app-interface">📸 Screenshots</a> •
    <a href="#-tech-stack">🛠️ Tech Stack</a> •
    <a href="#-installation-guide">⚙️ Setup</a>
  </p>
  
  <p align="center">
    <img src="https://img.shields.io/badge/version-1.0.0-blue?style=for-the-badge&logo=appveyor" alt="Version">
    <img src="https://img.shields.io/badge/platform-web-lightgrey?style=for-the-badge&logo=google-chrome" alt="Platform">
    <img src="https://img.shields.io/badge/license-MIT-orange?style=for-the-badge&logo=gitbook" alt="License">
    <img src="https://img.shields.io/badge/maintained-yes-green?style=for-the-badge&logo=github" alt="Maintained">
  </p>
</div>

<br />

## 🦅 Project Overview

**Crow Education** is designed to address the digital divide in the Malaysian education system. By leveraging open-source technology, we provide a **zero-cost** alternative to expensive tuition, ensuring that financial status does not dictate academic success.

> **💡 The Core Mission:** "To create a self-sustaining, community-driven educational platform where knowledge is free and accessible to every SPM student."

## 🎥 Project Walkthrough

<div align="center">
  <p><b>Watch the full 30-minute demonstration of the Crow Education Platform:</b></p>
  
  <a href="https://www.youtube.com/watch?v=-i-n_AmVFpw">
    <img src="https://img.youtube.com/vi/-i-n_AmVFpw/maxresdefault.jpg" width="100%" alt="Watch the Video">
  </a>
  
  <p>
    👆 <i>Click the image above to play the video</i>
  </p>
</div>
---

## ✨ Key Features

<table>
  <tr>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>📚</h1>
      <h3>Smart Library</h3>
      <p>Curated notes organized by subject, chapter, and difficulty level for easy access.</p>
    </td>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>📝</h1>
      <h3>Interactive Quizzes</h3>
      <p>Real-time grading with instant feedback and answer explanations.</p>
    </td>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>🎨</h1>
      <h3>Gamified Learning</h3>
      <p>Students earn badges and XP to stay motivated and track progress.</p>
    </td>
  </tr>
  <tr>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>👨‍🏫</h1>
      <h3>Teacher Portal</h3>
      <p>Dedicated interface for educators to upload materials and monitor class performance.</p>
    </td>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>📊</h1>
      <h3>Analytics Dashboard</h3>
      <p>Visual graphs showing student strengths and areas for improvement.</p>
    </td>
    <td width="33%" align="center" valign="top">
      <br>
      <h1>📱</h1>
      <h3>Mobile Responsive</h3>
      <p>Fully optimized for mobile devices, allowing learning on the go.</p>
    </td>
  </tr>
</table>

---

## 🛠️ Built With

We utilized a robust **LAMP stack** environment to ensure stability and ease of deployment on local servers.

| Frontend | Backend | Database | Tools |
| :--- | :--- | :--- | :--- |
| ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) | ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat&logo=mysql&logoColor=white) | ![Git](https://img.shields.io/badge/GIT-E44C30?style=flat&logo=git&logoColor=white) |
| ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white) | ![Apache](https://img.shields.io/badge/Apache-D22128?style=flat&logo=apache&logoColor=white) | ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=flat&logo=xampp&logoColor=white) | ![VS Code](https://img.shields.io/badge/VS_Code-0078D4?style=flat&logo=visual%20studio%20code&logoColor=white) |
| ![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=flat&logo=javascript&logoColor=F7DF1E) | | | ![Figma](https://img.shields.io/badge/Figma-F24E1E?style=flat&logo=figma&logoColor=white) |

---

## 💻 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites
* **XAMPP** (Installed and running)
* **Git** (Installed)

### Installation

1.  **Clone the repo** into your htdocs folder:
    ```sh
    cd C:\xampp\htdocs
    git clone [https://github.com/YOUR-USERNAME/crow-education.git](https://github.com/YOUR-USERNAME/crow-education.git)
    ```

2.  **Setup Database**:
    * Open `http://localhost/phpmyadmin`
    * Create a database named `crow_db`
    * Import `crow_db.sql` from the project folder.

3.  **Configure Connection**:
    * Open `includes/db_connect.php` (or your connection file)
    * Ensure settings match:
    ```php
    $servername = "localhost";
    $username = "root";
    $password = ""; // Default XAMPP password is empty
    $dbname = "crow_db";
    ```

4.  **Run**:
    * Open browser and go to `http://localhost/crow-education`

---

## 🗺️ Roadmap

- [x] **Phase 1:** Core platform launch (User Auth, Subject Listing)
- [ ] **Phase 2:** Quiz Engine implementation
- [ ] **Phase 3:** User Progress Dashboard
- [ ] **Phase 4:** Gamification (Badges & Leaderboards)

---

## 📂 Folder Structure

```text
crow-education/
├── 📂 assets/          # Images, CSS, JS files
├── 📂 includes/        # Database connection & helper functions
├── 📂 pages/           # Individual page content
├── 📄 index.php        # Main landing page
├── 📄 login.php        # User authentication
└── 📄 README.md        # Documentation
