<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sexual Assault Survivors - Support Group - MoodHelper</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
    <div class="container">
        <a href="dashboard.php" class="logo">
            <span class="logo-icon">❤️</span>
            <span class="logo-text">MoodHelper</span>
        </a>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="">Dashboard</a></li>
            <li><a href="diary.php" class="">Diary</a></li>
            <li><a href="daily-prompt.php" class="">Prompts</a></li>
            <li><a href="groups.php" class="">Groups</a></li>
            <li><a href="mood-support.php" class="">Support</a></li>
            <li><a href="settings.php" class="">Settings</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="account.php" class="btn btn-secondary">Account</a>
            <a href="index.html" class="btn btn-primary">Logout</a>
        </div>
    </div>
</nav>

    <div class="container" style="padding: 3rem 2rem; max-width: 900px;">
        <!-- Group Header -->
        <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(236, 72, 153, 0.03), rgba(236, 72, 153, 0.08));">
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: rgba(236, 72, 153, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                    🫂
                </div>
                <h1 style="font-size: 2rem; margin-bottom: 0.75rem;">
                    Sexual Assault Survivors
                </h1>
                <p style="color: var(--text-secondary); font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
                    A safe space for survivors to share, heal, and support one another through their journey.
                </p>
            </div>
        </div>

        <!-- Safe Space Guidelines -->
        <div class="card" style="margin-bottom: 2rem; border-left: 4px solid #ec4899;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>🛡️</span>
                <span>Safe Space Guidelines</span>
            </h3>
            <div style="color: var(--text-secondary); line-height: 1.8;">
                <p style="margin-bottom: 0.75rem;">
                    This is a moderated, judgment-free zone for survivors. Please remember:
                </p>
                <ul style="margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>All posts and identities are completely anonymous</li>
                    <li>Be compassionate, supportive, and respectful</li>
                    <li>Share your experience, not advice (unless someone asks)</li>
                    <li>No victim-blaming, harmful content, or triggering details</li>
                    <li>You are believed, you are valid, you are not alone</li>
                </ul>
                <p style="font-size: 0.875rem; color: #ec4899; font-weight: 500;">
                    If you're in crisis, please reach out to a crisis hotline or emergency services.
                </p>
            </div>
        </div>

        <!-- New Post Section -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem;">
                Share Your Thoughts
            </h3>
            <div class="form-group">
                <label>Post Title (Optional)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="postTitle"
                    placeholder="Give your post a title..."
                >
            </div>
            <div class="form-group">
                <label>Your Message</label>
                <textarea 
                    class="form-control" 
                    id="postContent"
                    placeholder="Share what's on your mind. You're safe here..."
                    rows="5"
                ></textarea>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="btn btn-primary" id="submitPost">
                    Post Anonymously
                </button>
                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                    🔒 Your identity is completely protected
                </span>
            </div>
        </div>

        <!-- Posts Section -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <span>Recent Posts</span>
                <select class="form-control" id="sortPosts" style="width: auto; padding: 0.5rem 1rem; font-size: 0.875rem;">
                    <option value="recent">Most Recent</option>
                    <option value="popular">Most Supported</option>
                </select>
            </h2>

            <div id="postsContainer">
                <!-- Sample Post 1 -->
                <div class="card" style="margin-bottom: 1.5rem; position: relative;">
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ec4899, #be185d); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                A
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                <span style="font-weight: 500; color: var(--text-primary);">Anonymous User</span>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">2 hours ago</span>
                            </div>
                            <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; color: var(--text-primary);">
                                Finally feeling like myself again
                            </h4>
                            <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 1rem;">
                                I just wanted to share that after months of therapy and support from this community, 
                                I'm starting to feel like myself again. The nightmares are less frequent, and I can 
                                go outside without constant fear. To anyone just starting their journey: healing isn't 
                                linear, but it is possible. Thank you all for being here. 💜
                            </p>
                            
                            <!-- Reactions & Actions -->
                            <div style="display: flex; gap: 1.5rem; align-items: center; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s;">
                                    <span>💜</span>
                                    <span style="font-weight: 500;">24 Hearts</span>
                                </button>
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s;">
                                    <span>💬</span>
                                    <span style="font-weight: 500;">5 Replies</span>
                                </button>
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: all 0.2s;">
                                    <span>🫂</span>
                                    <span style="font-weight: 500;">Send Support</span>
                                </button>
                            </div>

                            <!-- Replies Section (collapsed by default) -->
                            <div id="replies-1" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light);">
                                <!-- Reply 1 -->
                                <div style="display: flex; gap: 0.75rem; margin-bottom: 1rem; padding-left: 1rem; border-left: 2px solid var(--border-light);">
                                    <div style="flex-shrink: 0;">
                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #a855f7, #7c3aed); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                            B
                                        </div>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                            <span style="font-weight: 500; font-size: 0.875rem;">Anonymous User</span>
                                            <span style="color: var(--text-secondary); font-size: 0.75rem;">1 hour ago</span>
                                        </div>
                                        <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6;">
                                            This gives me so much hope. I'm so proud of you for how far you've come. 💜
                                        </p>
                                    </div>
                                </div>

                                <!-- Reply Input -->
                                <div style="margin-top: 1rem; padding-left: 1rem;">
                                    <textarea 
                                        class="form-control" 
                                        placeholder="Write a supportive reply..."
                                        rows="2"
                                        style="font-size: 0.9rem; margin-bottom: 0.5rem;"
                                    ></textarea>
                                    <button class="btn btn-secondary btn-small">
                                        Reply Anonymously
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Post 2 -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #3b82f6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                C
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                <span style="font-weight: 500; color: var(--text-primary);">Anonymous User</span>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">5 hours ago</span>
                            </div>
                            <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; color: var(--text-primary);">
                                How do you deal with victim-blaming from family?
                            </h4>
                            <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 1rem;">
                                I finally opened up to my family about what happened, and instead of support, I got 
                                questions about what I was wearing and why I was there. I feel so alone and invalidated. 
                                How do you cope when the people closest to you don't believe or support you?
                            </p>
                            
                            <div style="display: flex; gap: 1.5rem; align-items: center; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">
                                    <span>💜</span>
                                    <span style="font-weight: 500;">18 Hearts</span>
                                </button>
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">
                                    <span>💬</span>
                                    <span style="font-weight: 500;">12 Replies</span>
                                </button>
                                <button class="btn-small" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.5rem;">
                                    <span>🫂</span>
                                    <span style="font-weight: 500;">Send Support</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State (hidden when posts exist) -->
                <div id="emptyState" style="display: none; text-align: center; padding: 4rem 2rem; color: var(--text-secondary);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">No posts yet</h3>
                    <p>Be the first to share your story or ask for support.</p>
                </div>
            </div>
        </div>

        <!-- Community Resources -->
        
        <div class="card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.1));">
            <h3 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <span style="font-size: 1.25rem;">🫶</span>
                <span style="font-size: 1.25rem; color: var(--text-primary);">Support & Grounding Tools</span>
            </h3>
            <p style="color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.7;">
                If you feel overwhelmed while reading or posting, try one of these quick tools:
            </p>
            <div style="display: grid; gap: 0.75rem;">
                <div style="padding: 0.75rem; background: white; border-radius: 0.75rem; border: 1px solid var(--border-light);">
                    <strong style="color: var(--text-primary);">5–4–3–2–1 Grounding:</strong>
                    <span style="color: var(--text-secondary);"> Name 5 things you see, 4 you feel, 3 you hear, 2 you smell, 1 you taste.</span>
                </div>
                <div style="padding: 0.75rem; background: white; border-radius: 0.75rem; border: 1px solid var(--border-light);">
                    <strong style="color: var(--text-primary);">Box Breathing:</strong>
                    <span style="color: var(--text-secondary);"> Inhale 4 • Hold 4 • Exhale 4 • Hold 4 — repeat 4 times.</span>
                </div>
                <div style="padding: 0.75rem; background: white; border-radius: 0.75rem; border: 1px solid var(--border-light);">
                    <strong style="color: var(--text-primary);">Reach Out:</strong>
                    <span style="color: var(--text-secondary);"> If you’re in immediate danger or crisis, contact local emergency services or a trusted person near you.</span>
                </div>
            </div>
        </div>
</div>
    </div>

    <script src="js/group-page.js"></script>
</body>
</html>
