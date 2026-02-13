# MoodHelper - Frontend Pages by Ranim

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

### 3. Dashboard (dashboard.html)
- Emotion check-in with 6 mood options
- Text input for daily feelings
- Anonymous message option
- Quick access cards to all features
- Clean, organized layout

### 4. Mood Support / AI Chat (mood-support.html)
- AI chatbot interface for emotional support
- Personalized mood-lifting recommendations
- Interactive breathing exercise modal
- Calming sounds, videos, and journal prompts
- Warm, supportive messaging

### 5. Daily Prompt (daily-prompt.html)
- Daily reflection question
- Text area for thoughtful responses
- Weekly reflections display
- Streak counter and motivation
- Skip option available

### 6. Private Diary (diary.html)
- Private journal entries with titles
- Mood selection for each entry
- Edit and delete functionality
- Filter by time period (all/week/month)
- Statistics: total entries, streak, monthly count
- Completely private and secure

## Features

### Design Features
- **Calming Color Palette**: Purple and blue gradients with soft backgrounds
- **Responsive Design**: Works on desktop, tablet, and mobile
- **Smooth Animations**: Fade-in effects and hover states
- **Accessible UI**: Clear typography and intuitive navigation

### Functional Features
- **Theme Toggle**: Automatic calm theme for distressed users
- **Local Storage**: All data saved in browser (no server needed for demo)
- **Interactive Elements**: Click, select, and submit interactions
- **Toast Notifications**: User-friendly feedback messages
- **Data Persistence**: Mood entries, diary, and reflections saved locally

### Calming Theme
- Activated automatically for anxious/angry users
- Green and blue tones
- Softer, more soothing interface
- Can be toggled in settings

## Tech Stack

- **HTML5**: Semantic markup
- **CSS3**: Custom properties, gradients, animations
- **JavaScript (ES6+)**: Vanilla JS, no frameworks needed
- **LocalStorage**: Client-side data persistence

## File Structure

```
moodhelper-ranim/
├── index.html              # Home page
├── about.html              # About page
├── dashboard.html          # Main dashboard
├── mood-support.html       # AI chat support
├── daily-prompt.html       # Daily reflection
├── diary.html              # Private diary
├── css/
│   └── styles.css          # Main stylesheet
└── js/
    ├── main.js             # General utilities
    ├── dashboard.js        # Dashboard functionality
    ├── mood-support.js     # AI chat logic
    ├── daily-prompt.js     # Prompt system
    └── diary.js            # Diary functionality
```

## How to Use

2. **Open index.html** in your web browser
3. **Navigate** through the pages using the navigation menu
4. **Test the features**:
   - Select emotions on the dashboard
   - Try the AI chat when feeling sad/anxious
   - Write diary entries
   - Answer daily prompts
   - View your mood tracking

## Key Interactive Elements

### Dashboard
- Click emotion buttons to select your mood
- Fill in the text area with your feelings
- Check the box to send an anonymous message
- Submit to save or redirect to mood support

### Mood Support
- Type messages to interact with AI
- Click recommendation cards to try activities
- Start breathing exercise for calming technique
- Automatic theme switch offered for anxious users

### Daily Prompt
- Answer thought-provoking daily questions
- View your weekly reflections
- Track completion streak
- Skip if not feeling up to it

### Private Diary
- Write entries with optional titles
- Select your mood with emoji buttons
- Edit or delete past entries
- Filter entries by time period
- View journaling statistics

## Customization

### Colors
Edit `css/styles.css` and modify the CSS variables in `:root`:
```css
:root {
    --primary-purple: #7c3aed;
    --primary-blue: #3b82f6;
    /* etc. */
}
```

### Prompts
Edit `js/daily-prompt.js` to add more prompts:
```javascript
const dailyPrompts = [
    "Your new prompt here",
    // Add more...
];
```

### AI Responses
Edit `js/mood-support.js` to customize AI responses:
```javascript
const aiResponses = {
    sad: [ "Your responses..." ],
    // etc.
};
```

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

## Notes

- All data is stored in browser localStorage
- No backend/server required for demo
- Data persists between sessions
- Clear browser data will reset everything
- Fully functional offline

## Privacy

This is a frontend-only demo. In a production environment:
- Use secure backend for data storage
- Implement proper authentication
- Add encryption for sensitive data
- Follow HIPAA/GDPR compliance as needed

## Future Enhancements

Suggested features for full implementation:
- Real AI integration (Gemini API)
- User authentication system
- Cloud data synchronization
- Actual calming sounds/videos
- Community features (reflection board, groups)
- Mobile app version
- Professional therapist directory

## Credits

Created by: Ranim Ibrahim and Antonio Karam
Project: MoodHelper Capstone Project
Course: CSC 599

---

For questions or issues, please contact the development team.

Enjoy using MoodHelper! 💜
