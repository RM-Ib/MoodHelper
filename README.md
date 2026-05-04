# MoodHelper 
A calming, supportive mental health and mood-lifting web application with anonymous emotional support features.

## Pages Included

### 1. Home Page (index.html)
- Welcoming introduction to MoodHelper
- Feature highlights with icons
- Call-to-action buttons
- Responsive navigation bar
- Clean, calming design

### 2. About Page (about.html)
- Mission statement
- What makes MoodHelper different
- What users can expect
- Ethical commitment section
- Detailed explanation of features

### 3. Dashboard (dashboard.php)
- Emotion check-in with 6 mood options
- Text input for daily feelings
- Anonymous message option
- Quick access cards to all features
- Clean, organized layout

### 4. Authentication
 # Signup (signup.php)
 - User registration
 - Stores user credentials securely in database
 # Login (login.php)
 - User authentication
 - Session handling
 # Account (account.php)
 - User profile management
 - View/update personal info

### 5. Mood Support / AI Chat (mood-support.php)
AI chatbot for emotional support
Detects harmful or distress language
Provides:
Coping strategies
Breathing exercises
Supportive responses

### 6. Daily Prompt (daily-prompt.php)
- Daily reflection question
- Text area for thoughtful responses
- User responses saved in database
- Weekly reflections display
- Streak counter and motivation
- Skip option available

### 7. Private Diary (diary.php)
- Private journal entries with titles
- Edit and delete functionality
- Filter by time period (all/week/month)
- Statistics: total entries, streak, monthly count
- Completely private and secure

### 8. Mood Tracking (mood-tracking.php)
- Tracks user mood history
- Displays patters over time
- Helps identify emotional trends

### 9. Reflection Board (reflection-board.php)
- Shared anonymous reflections
- users can read other's thoughts
- encourages connection and empathy

### 10. Community groups 
   # Groups List (groups.php)
    - view available support groups

   # Group Chat (group.php)
    - join group discussions
    - share experiences with others

## Features

## Tech Stack
# Frontend
- HTML5
- CSS3 (Responsive design, animations)
- JavaScript (Vanilla JS)
# Backend
- PHP
- MySQL (Database: moodhelperdb.sql)
- Session management


## File Structure

MoodHelper/
│
├── index.html
├── about.html
│
├── signup.php
├── login.php
├── account.php
│
├── dashboard.php
├── mood-support.php
├── mood-tracking.php
├── daily-prompt.php
├── diary.php
├── reflection-board.php
├── groups.php
├── group.php
│
├── Backend/
│   └── (database + server logic)
│
├── css/styles.css
├── js/(javascript files)
├── Images/ logo image
│
├── moodhelperdb.sql
└── README.md

## How to Use

After setting up and running the project locally, start by creating an account through the signup page and logging in. Once authenticated, you will be directed to the dashboard, where you can record your current mood and access all core features. Use the mood support chatbot if you need immediate emotional assistance, or explore the daily prompt and diary pages to reflect and write privately about your thoughts. Your mood entries are automatically tracked over time and can be viewed in the mood tracking section to help you recognize patterns. You can also visit the reflection board to read shared experiences or join community groups to interact with others. All features are accessible through the navigation menu, allowing you to move smoothly between tracking, reflection, and support tools.

## Credits

Created by: Ranim Ibrahim and Antonio Karam
Project: MoodHelper Capstone Project
Course: CSC 599

---

For questions or issues, please do not hesitate to contact us by email.

Enjoy using MoodHelper! 💜
