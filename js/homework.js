document.addEventListener("DOMContentLoaded", function () {
  const darkModeToggle = document.getElementById("darkModeToggle");
  const toggleIcon = darkModeToggle.querySelector(".toggle-icon");
  const toggleText = darkModeToggle.querySelector(".toggle-text");

  const isDarkMode = localStorage.getItem("darkMode") === "true";

  if (isDarkMode) {
    document.body.classList.add("dark-mode");
    toggleIcon.classList.remove("fa-moon");
    toggleIcon.classList.add("fa-sun");
    toggleText.textContent = "Light Mode";
  }

  darkModeToggle.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
      toggleIcon.classList.remove("fa-moon");
      toggleIcon.classList.add("fa-sun");
      toggleText.textContent = "Light Mode";
      localStorage.setItem("darkMode", "true");
    } else {
      toggleIcon.classList.remove("fa-sun");
      toggleIcon.classList.add("fa-moon");
      toggleText.textContent = "Dark Mode";
      localStorage.setItem("darkMode", "false");
    }
  });

  const menuItems = document.querySelectorAll(".menu-item");
  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      menuItems.forEach((i) => i.classList.remove("active"));
      this.classList.add("active");
    });
  });

  const studentItems = document.querySelectorAll(".student-item");
  studentItems.forEach((item) => {
    item.addEventListener("click", function () {
      studentItems.forEach((i) => i.classList.remove("active"));
      this.classList.add("active");

      const studentId = this.getAttribute("data-student");
      const studentName = this.querySelector(".student-name").textContent;

      document.querySelector(
        ".card-title"
      ).textContent = `Question Review - ${studentName}`;

      loadStudentQuestions(studentId);
    });
  });

  document
    .getElementById("saveFeedbackBtn")
    .addEventListener("click", function () {
      this.innerHTML = '<i class="fas fa-check"></i> Feedback Saved';
      this.style.backgroundColor = "#38b000";

      setTimeout(() => {
        this.innerHTML = '<i class="fas fa-save"></i> Save Feedback';
        this.style.backgroundColor = "";
      }, 2000);

      alert("Feedback saved successfully!");
    });

  const headerSearch = document.querySelector(".search-box input");
  headerSearch.addEventListener("input", function () {
    if (this.value.length > 0) {
      const studentItems = document.querySelectorAll(".student-item");
      studentItems.forEach((item) => {
        const name = item
          .querySelector(".student-name")
          .textContent.toLowerCase();
        if (name.includes(this.value.toLowerCase())) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    } else {
      const studentItems = document.querySelectorAll(".student-item");
      studentItems.forEach((item) => {
        item.style.display = "block";
      });
    }
  });

  const footerToggles = document.querySelectorAll(".footer-toggle");
  footerToggles.forEach((toggle) => {
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

  function loadStudentQuestions(studentId) {
    const questionReview = document.querySelector(".question-review");

    questionReview.innerHTML = "";

    const studentQuestions = {
      1: [
        {
          title: "Question 1: Solve for x: 2x + 5 = 15",
          content: "Find the value of x in the equation: 2x + 5 = 15",
          correctAnswer: "x = 5",
          correctExplanation:
            "Subtract 5 from both sides: 2x = 10, then divide by 2: x = 5",
          studentAnswer: "x = 10",
          errorAnalysis:
            "Student forgot to divide by 2 after subtracting 5 from both sides.",
          status: "incorrect",
          feedback:
            "Remember to perform the same operation on both sides of the equation. After subtracting 5, you need to divide by 2 to isolate x.",
        },
        {
          title: "Question 7: Calculate the area of a circle with radius 7cm",
          content:
            "Use π = 3.14 to calculate the area of a circle with radius 7cm.",
          correctAnswer: "153.86 cm²",
          correctExplanation: "Area = πr² = 3.14 × 7² = 3.14 × 49 = 153.86",
          studentAnswer: "43.96 cm²",
          errorAnalysis:
            "Student calculated the circumference (2πr) instead of the area (πr²).",
          status: "incorrect",
          feedback:
            "Remember the formula for area of a circle is πr², not 2πr which is the circumference formula.",
        },
        {
          title: "Question 12: Simplify 3(x + 4) - 2x",
          content: "Simplify the expression: 3(x + 4) - 2x",
          correctAnswer: "x + 12",
          correctExplanation: "Distribute 3: 3x + 12 - 2x = x + 12",
          studentAnswer: "x + 12",
          errorAnalysis:
            "Student correctly distributed and combined like terms.",
          status: "correct",
          feedback:
            "Well done! You correctly applied the distributive property and combined like terms.",
        },
      ],
      2: [
        {
          title: "Question 3: Find the slope of the line y = 2x + 3",
          content:
            "Determine the slope of the line given by the equation y = 2x + 3",
          correctAnswer: "2",
          correctExplanation:
            "The equation is in slope-intercept form y = mx + b, where m is the slope",
          studentAnswer: "3",
          errorAnalysis: "Student confused the slope with the y-intercept",
          status: "incorrect",
          feedback:
            "Remember that in y = mx + b, m is the slope and b is the y-intercept.",
        },
        {
          title: "Question 8: Solve the inequality 3x - 7 > 8",
          content:
            "Find all values of x that satisfy the inequality: 3x - 7 > 8",
          correctAnswer: "x > 5",
          correctExplanation:
            "Add 7 to both sides: 3x > 15, then divide by 3: x > 5",
          studentAnswer: "x > 3",
          errorAnalysis: "Student incorrectly divided 15 by 5 instead of 3",
          status: "incorrect",
          feedback:
            "Be careful with division when solving inequalities. 15 divided by 3 is 5, not 3.",
        },
        {
          title: "Question 15: Factor x² + 5x + 6",
          content: "Factor the quadratic expression: x² + 5x + 6",
          correctAnswer: "(x + 2)(x + 3)",
          correctExplanation:
            "Find two numbers that multiply to 6 and add to 5: 2 and 3",
          studentAnswer: "(x + 1)(x + 6)",
          errorAnalysis:
            "Student found numbers that multiply to 6 but add to 7, not 5",
          status: "incorrect",
          feedback:
            "When factoring quadratics, make sure the numbers multiply to the constant term and add to the coefficient of x.",
        },
      ],
      3: [
        {
          title: "Question 5: Calculate 3/4 + 1/2",
          content: "Add the fractions: 3/4 + 1/2",
          correctAnswer: "5/4 or 1 1/4",
          correctExplanation: "Find common denominator (4): 3/4 + 2/4 = 5/4",
          studentAnswer: "4/6",
          errorAnalysis:
            "Student added numerators and denominators directly without finding common denominator",
          status: "incorrect",
          feedback:
            "Remember to find a common denominator before adding fractions.",
        },
        {
          title: "Question 11: Solve the system: y = 2x + 1 and y = -x + 4",
          content: "Find the point of intersection of the two lines",
          correctAnswer: "(1, 3)",
          correctExplanation:
            "Set equations equal: 2x + 1 = -x + 4 → 3x = 3 → x = 1, then y = 2(1) + 1 = 3",
          studentAnswer: "(2, 2)",
          errorAnalysis:
            "Student made calculation error when solving the system",
          status: "incorrect",
          feedback:
            "Double-check your calculations when solving systems of equations.",
        },
        {
          title: "Question 18: Find the derivative of f(x) = 3x²",
          content: "Calculate the derivative of the function f(x) = 3x²",
          correctAnswer: "6x",
          correctExplanation:
            "Apply power rule: derivative of 3x² is 2*3x^(2-1) = 6x",
          studentAnswer: "6x",
          errorAnalysis: "Student correctly applied the power rule",
          status: "correct",
          feedback: "Excellent work applying the power rule for derivatives!",
        },
      ],
    };

    studentQuestions[studentId].forEach((question) => {
      const questionItem = document.createElement("div");
      questionItem.className = `question-item ${question.status}`;
      questionItem.innerHTML = `
                        <div class="question-header">
                            <h3 class="question-title">${question.title}</h3>
                            <span class="question-status status-${
                              question.status
                            }">${
        question.status === "correct" ? "Correct" : "Incorrect"
      }</span>
                        </div>
                        <div class="question-content">
                            <p>${question.content}</p>
                        </div>
                        <div class="answer-section">
                            <div class="answer-box correct-answer">
                                <h4>Correct Answer</h4>
                                <p>${question.correctAnswer}</p>
                                <p><strong>Explanation:</strong> ${
                                  question.correctExplanation
                                }</p>
                            </div>
                            <div class="answer-box student-answer">
                                <h4>Student's Answer</h4>
                                <p>${question.studentAnswer}</p>
                                <p><strong>Error Analysis:</strong> ${
                                  question.errorAnalysis
                                }</p>
                            </div>
                        </div>
                        <div class="feedback-section">
                            <h4>Teacher Feedback</h4>
                            <textarea class="feedback-input" placeholder="Enter feedback for the student...">${
                              question.feedback
                            }</textarea>
                        </div>
                    `;
      questionReview.appendChild(questionItem);
    });
  }
});
