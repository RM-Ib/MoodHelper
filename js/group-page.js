// js/group-page.js
// Support Group Page Functionality (posts + replies + like on posts & comments)
// Works with group-sa.html structure (postsContainer, emptyState, sortPosts, submitPost, postTitle, postContent)

(function () {
  "use strict";

  // ---------- Helpers: IDs / storage ----------
  function getGroupIdFromURL() {
    const file = (window.location.pathname.split("/").pop() || "").toLowerCase();

    // Supports naming like: group-sa.html
    const m = file.match(/^group-(.+)\.html$/);
    if (m) return m[1];

    // Fallbacks if you ever rename pages later
    if (file.includes("sa")) return "sa";
    return "unknown";
  }

  function storageKey(groupId) {
    return `group_posts_${groupId}`;
  }

  function loadPosts() {
    const groupId = getGroupIdFromURL();
    const key = storageKey(groupId);
    const stored = localStorage.getItem(key);

    if (!stored) {
      const seed = getSeedPosts(groupId);
      localStorage.setItem(key, JSON.stringify(seed));
      return seed;
    }

    try {
      const parsed = JSON.parse(stored);
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      // corrupted storage -> reset to seed
      const seed = getSeedPosts(groupId);
      localStorage.setItem(key, JSON.stringify(seed));
      return seed;
    }
  }

  function savePosts(posts) {
    const groupId = getGroupIdFromURL();
    localStorage.setItem(storageKey(groupId), JSON.stringify(posts));
  }

  // ---------- Seed posts ----------
  function getSeedPosts(groupId) {
    const now = Date.now();
    const isoHoursAgo = (h) => new Date(now - h * 3600_000).toISOString();

    // Only seed SA group for now (matches your project)
    if (groupId === "sa") {
      return [
        {
          id: "seed_sa_1",
          title: "Finally feeling like myself again",
          content:
            "I just wanted to share that after months of therapy and support from this community, I'm starting to feel like myself again. The nightmares are less frequent, and I can go outside without constant fear. To anyone just starting their journey: healing isn't linear, but it is possible. Thank you all for being here. 💜",
          timestamp: isoHoursAgo(2),
          avatarColor: "linear-gradient(135deg, #ec4899, #be185d)",
          avatarLetter: "A",
          hearts: 24,
          replies: [
            {
              id: "seed_sa_r1",
              content:
                "This gives me so much hope. I'm so proud of you for how far you've come. 💜",
              timestamp: isoHoursAgo(1),
              avatarColor: "linear-gradient(135deg, #a855f7, #7c3aed)",
              avatarLetter: "B",
              hearts: 2,
              replies: [
                {
                  id: "seed_sa_r1_c1",
                  content: "Same here… reading this felt like a breath of air.",
                  timestamp: isoHoursAgo(0.7),
                  avatarColor: "linear-gradient(135deg, #6366f1, #3b82f6)",
                  avatarLetter: "C",
                },
              ],
            },
          ],
        },
        {
          id: "seed_sa_2",
          title: "How do you deal with victim-blaming from family?",
          content:
            "I finally opened up to my family about what happened, and instead of support, I got questions about what I was wearing and why I was there. I feel so alone and invalidated. How do you cope when the people closest to you don't believe or support you?",
          timestamp: isoHoursAgo(5),
          avatarColor: "linear-gradient(135deg, #6366f1, #3b82f6)",
          avatarLetter: "C",
          hearts: 18,
          replies: [],
        },
      ];
    }

    return [];
  }

  // ---------- Avatar helpers ----------
  function getRandomAvatarColor() {
    const colors = [
      "linear-gradient(135deg, #ec4899, #be185d)",
      "linear-gradient(135deg, #6366f1, #3b82f6)",
      "linear-gradient(135deg, #a855f7, #7c3aed)",
      "linear-gradient(135deg, #14b8a6, #0d9488)",
      "linear-gradient(135deg, #f59e0b, #d97706)",
      "linear-gradient(135deg, #10b981, #059669)",
    ];
    return colors[Math.floor(Math.random() * colors.length)];
  }

  function getRandomLetter() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return letters[Math.floor(Math.random() * letters.length)];
  }

  // ---------- Time formatting ----------
  function getTimeAgo(timestamp) {
    const now = new Date();
    const posted = new Date(timestamp);
    const diffMs = now - posted;

    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return "Just now";
    if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? "s" : ""} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? "s" : ""} ago`;
    return `${diffDays} day${diffDays > 1 ? "s" : ""} ago`;
  }

  // ---------- Rendering ----------
  function escapeHTML(s) {
    return String(s)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  function createPostHTML(post) {
    const repliesCount = Array.isArray(post.replies) ? post.replies.length : 0;

    return `
      <div class="card" style="margin-bottom: 1.5rem;" data-post-id="${post.id}">
        <div style="display:flex; gap:1rem;">
          <div style="flex-shrink:0;">
            <div style="width:40px; height:40px; background:${post.avatarColor}; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:600;">
              ${escapeHTML(post.avatarLetter || "A")}
            </div>
          </div>

          <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
              <span style="font-weight:500; color:var(--text-primary);">Anonymous User</span>
              <span style="color:var(--text-secondary); font-size:0.875rem;">${getTimeAgo(
                post.timestamp
              )}</span>
            </div>

            ${
              post.title
                ? `<h4 style="font-size:1.125rem; margin-bottom:0.75rem; color:var(--text-primary);">${escapeHTML(
                    post.title
                  )}</h4>`
                : ""
            }

            <p style="color:var(--text-secondary); line-height:1.7; margin-bottom:1rem; white-space:pre-wrap;">
              ${escapeHTML(post.content)}
            </p>

            <!-- Actions -->
            <div style="display:flex; gap:1.5rem; align-items:center; padding-top:0.75rem; border-top:1px solid var(--border-light);">
              <button class="btn-small heart-btn"
                data-post-id="${post.id}"
                style="display:flex; align-items:center; gap:0.5rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.5rem 0.75rem; border-radius:0.5rem; transition:all 0.2s;">
                <span>💜</span>
                <span style="font-weight:500;"><span class="heart-count">${post.hearts || 0}</span> Hearts</span>
              </button>

              <button class="btn-small toggle-replies"
                data-post-id="${post.id}"
                style="display:flex; align-items:center; gap:0.5rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.5rem 0.75rem; border-radius:0.5rem; transition:all 0.2s;">
                <span>💬</span>
                <span style="font-weight:500;"><span class="reply-total">${repliesCount}</span> Replies</span>
              </button>

              <button class="btn-small support-btn"
                data-post-id="${post.id}"
                style="display:flex; align-items:center; gap:0.5rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.5rem 0.75rem; border-radius:0.5rem; transition:all 0.2s;">
                <span>🫂</span>
                <span style="font-weight:500;">Send Support</span>
              </button>
            </div>

            <!-- Replies section -->
            <div class="replies-section" data-post-id="${post.id}"
              style="display:none; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--border-light);">

              <div class="replies-list">
                ${(post.replies || []).map((r) => createReplyHTML(post.id, r)).join("")}
              </div>

              <!-- Reply input -->
              <div style="margin-top:1rem; padding-left:1rem;">
                <textarea class="form-control reply-input"
                  data-post-id="${post.id}"
                  placeholder="Write a supportive reply..."
                  rows="2"
                  style="font-size:0.9rem; margin-bottom:0.5rem;"></textarea>

                <button class="btn btn-secondary btn-small submit-reply"
                  data-post-id="${post.id}">
                  Reply Anonymously
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
    `;
  }

  function createReplyHTML(postId, reply) {
    const hearts = reply.hearts || 0;
    const childCount = Array.isArray(reply.replies) ? reply.replies.length : 0;

    return `
      <div class="reply-item" data-post-id="${postId}" data-reply-id="${reply.id}"
        style="display:flex; gap:0.75rem; margin-bottom:1rem; padding-left:1rem; border-left:2px solid var(--border-light);">

        <div style="flex-shrink:0;">
          <div style="width:32px; height:32px; background:${reply.avatarColor}; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:600; font-size:0.875rem;">
            ${escapeHTML(reply.avatarLetter || "M")}
          </div>
        </div>

        <div style="flex:1;">
          <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
            <span style="font-weight:500; font-size:0.875rem;">Anonymous User</span>
            <span style="color:var(--text-secondary); font-size:0.75rem;">${getTimeAgo(
              reply.timestamp
            )}</span>
          </div>

          <p style="color:var(--text-secondary); font-size:0.9rem; line-height:1.6; white-space:pre-wrap; margin-bottom:0.5rem;">
            ${escapeHTML(reply.content)}
          </p>

          <!-- Comment actions -->
          <div style="display:flex; gap:1rem; align-items:center; margin-bottom:0.75rem;">
            <button class="btn-small reply-heart-btn"
              data-post-id="${postId}" data-reply-id="${reply.id}"
              style="display:flex; align-items:center; gap:0.4rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.25rem 0.5rem; border-radius:0.5rem;">
              <span>💜</span>
              <span style="font-weight:500;"><span class="reply-heart-count">${hearts}</span></span>
            </button>

            <button class="btn-small toggle-reply-thread"
              data-post-id="${postId}" data-reply-id="${reply.id}"
              style="display:flex; align-items:center; gap:0.4rem; background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:0.25rem 0.5rem; border-radius:0.5rem;">
              <span>↩️</span>
              <span style="font-weight:500;">Reply</span>
              ${
                childCount > 0
                  ? `<span style="color:var(--text-secondary); font-size:0.8rem;">(${childCount})</span>`
                  : ""
              }
            </button>
          </div>

          <!-- Nested replies thread -->
          <div class="reply-thread"
            data-post-id="${postId}" data-reply-id="${reply.id}"
            style="display:none; padding:0.75rem 0.75rem; background:rgba(255,255,255,0.6); border:1px solid var(--border-light); border-radius:0.75rem;">

            <div class="nested-replies-list">
              ${(reply.replies || []).map((child) => createNestedReplyHTML(child)).join("")}
            </div>

            <div style="margin-top:0.5rem;">
              <textarea class="form-control reply-to-reply-input"
                data-post-id="${postId}" data-reply-id="${reply.id}"
                placeholder="Write a kind reply..."
                rows="2"
                style="font-size:0.9rem; margin-bottom:0.5rem;"></textarea>

              <button class="btn btn-secondary btn-small submit-reply-to-reply"
                data-post-id="${postId}" data-reply-id="${reply.id}">
                Reply
              </button>
            </div>
          </div>

        </div>
      </div>
    `;
  }

  function createNestedReplyHTML(child) {
    return `
      <div style="display:flex; gap:0.6rem; margin-bottom:0.75rem; padding-left:0.75rem; border-left:2px solid rgba(0,0,0,0.06);">
        <div style="flex-shrink:0;">
          <div style="width:28px; height:28px; background:${child.avatarColor}; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:600; font-size:0.8rem;">
            ${escapeHTML(child.avatarLetter || "U")}
          </div>
        </div>
        <div style="flex:1;">
          <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.15rem;">
            <span style="font-weight:500; font-size:0.85rem;">Anonymous User</span>
            <span style="color:var(--text-secondary); font-size:0.75rem;">${getTimeAgo(
              child.timestamp
            )}</span>
          </div>
          <p style="color:var(--text-secondary); font-size:0.88rem; line-height:1.6; white-space:pre-wrap; margin:0;">
            ${escapeHTML(child.content)}
          </p>
        </div>
      </div>
    `;
  }

  // ---------- Page actions ----------
  function displayPosts(sortBy = "recent") {
    const container = document.getElementById("postsContainer");
    const emptyState = document.getElementById("emptyState");

    if (!container) return;

    let posts = loadPosts();

    if (sortBy === "popular") {
      posts = posts.slice().sort((a, b) => (b.hearts || 0) - (a.hearts || 0));
    } else {
      posts = posts.slice().sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
    }

    if (posts.length === 0) {
      container.innerHTML = "";
      if (emptyState) emptyState.style.display = "block";
      return;
    }

    if (emptyState) emptyState.style.display = "none";
    container.innerHTML = posts.map(createPostHTML).join("");

    attachEventListeners();
  }

  function attachEventListeners() {
    // Toggle replies
    document.querySelectorAll(".toggle-replies").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const section = document.querySelector(`.replies-section[data-post-id="${postId}"]`);
        if (!section) return;
        section.style.display = section.style.display === "none" || section.style.display === "" ? "block" : "none";
      });
    });

    // Heart post
    document.querySelectorAll(".heart-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const posts = loadPosts();
        const post = posts.find((p) => p.id === postId);
        if (!post) return;

        post.hearts = (post.hearts || 0) + 1;
        savePosts(posts);

        const countEl = btn.querySelector(".heart-count");
        if (countEl) countEl.textContent = String(post.hearts);

        showToast("Heart sent! 💜");
      });
    });

    // Send support
    document.querySelectorAll(".support-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        showToast("Support sent! 🫂 They'll feel your kindness.");
      });
    });

    // Submit reply to post
    document.querySelectorAll(".submit-reply").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const input = document.querySelector(`.reply-input[data-post-id="${postId}"]`);
        const content = (input?.value || "").trim();
        if (!content) return showToast("Please write a reply first", "error");

        const posts = loadPosts();
        const post = posts.find((p) => p.id === postId);
        if (!post) return;

        if (!Array.isArray(post.replies)) post.replies = [];

        post.replies.push({
          id: String(Date.now()),
          content,
          timestamp: new Date().toISOString(),
          avatarColor: getRandomAvatarColor(),
          avatarLetter: getRandomLetter(),
          hearts: 0,
          replies: [],
        });

        savePosts(posts);

        const sort = document.getElementById("sortPosts");
        displayPosts(sort ? sort.value : "recent");

        showToast("Reply posted anonymously! 💬");
      });
    });

    // Like a comment
    document.querySelectorAll(".reply-heart-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const replyId = btn.getAttribute("data-reply-id");

        const posts = loadPosts();
        const post = posts.find((p) => p.id === postId);
        if (!post || !Array.isArray(post.replies)) return;

        const reply = post.replies.find((r) => r.id === replyId);
        if (!reply) return;

        reply.hearts = (reply.hearts || 0) + 1;
        savePosts(posts);

        const countEl = btn.querySelector(".reply-heart-count");
        if (countEl) countEl.textContent = String(reply.hearts);

        showToast("Support sent to their comment 💜");
      });
    });

    // Toggle nested reply thread under a comment
    document.querySelectorAll(".toggle-reply-thread").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const replyId = btn.getAttribute("data-reply-id");

        const thread = document.querySelector(
          `.reply-thread[data-post-id="${postId}"][data-reply-id="${replyId}"]`
        );
        if (!thread) return;

        thread.style.display = thread.style.display === "none" || thread.style.display === "" ? "block" : "none";
      });
    });

    // Submit nested reply to a comment
    document.querySelectorAll(".submit-reply-to-reply").forEach((btn) => {
      btn.addEventListener("click", () => {
        const postId = btn.getAttribute("data-post-id");
        const replyId = btn.getAttribute("data-reply-id");

        const input = document.querySelector(
          `.reply-to-reply-input[data-post-id="${postId}"][data-reply-id="${replyId}"]`
        );
        const content = (input?.value || "").trim();
        if (!content) return showToast("Please write a reply first", "error");

        const posts = loadPosts();
        const post = posts.find((p) => p.id === postId);
        if (!post || !Array.isArray(post.replies)) return;

        const reply = post.replies.find((r) => r.id === replyId);
        if (!reply) return;

        if (!Array.isArray(reply.replies)) reply.replies = [];

        reply.replies.push({
          id: String(Date.now()),
          content,
          timestamp: new Date().toISOString(),
          avatarColor: getRandomAvatarColor(),
          avatarLetter: getRandomLetter(),
        });

        savePosts(posts);

        const sort = document.getElementById("sortPosts");
        displayPosts(sort ? sort.value : "recent");

        showToast("Reply posted 💬");
      });
    });
  }

  // ---------- New post ----------
  function wireNewPost() {
    const submit = document.getElementById("submitPost");
    if (!submit) return;

    submit.addEventListener("click", () => {
      const titleEl = document.getElementById("postTitle");
      const contentEl = document.getElementById("postContent");

      const title = (titleEl?.value || "").trim();
      const content = (contentEl?.value || "").trim();

      if (!content) return showToast("Please write something before posting", "error");

      const posts = loadPosts();
      posts.push({
        id: String(Date.now()),
        title,
        content,
        timestamp: new Date().toISOString(),
        avatarColor: getRandomAvatarColor(),
        avatarLetter: getRandomLetter(),
        hearts: 0,
        replies: [],
      });

      savePosts(posts);

      if (titleEl) titleEl.value = "";
      if (contentEl) contentEl.value = "";

      const sort = document.getElementById("sortPosts");
      displayPosts(sort ? sort.value : "recent");

      showToast("Post shared anonymously! 💜");

      const container = document.getElementById("postsContainer");
      if (container) {
        setTimeout(() => {
          container.scrollIntoView({ behavior: "smooth", block: "start" });
        }, 200);
      }
    });
  }

  // ---------- Sorting ----------
  function wireSort() {
    const sort = document.getElementById("sortPosts");
    if (!sort) return;
    sort.addEventListener("change", () => displayPosts(sort.value));
  }

  // ---------- Toast ----------
  function showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.style.cssText = `
      position: fixed;
      top: 2rem;
      right: 2rem;
      padding: 1rem 1.5rem;
      background: ${type === "success" ? "#10b981" : "#ef4444"};
      color: white;
      border-radius: 0.75rem;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
      z-index: 10000;
      font-weight: 500;
      max-width: 320px;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transition = "opacity 0.3s ease-out";
      setTimeout(() => toast.remove(), 300);
    }, 2500);
  }

  // ---------- Hover polish ----------
  function injectHoverCSS() {
    const style = document.createElement("style");
    style.textContent = `
      .heart-btn:hover, .toggle-replies:hover, .support-btn:hover,
      .reply-heart-btn:hover, .toggle-reply-thread:hover {
        background: rgba(124, 58, 237, 0.05) !important;
        color: var(--primary-purple) !important;
      }
    `;
    document.head.appendChild(style);
  }

  // ---------- Init ----------
  document.addEventListener("DOMContentLoaded", () => {
    // Only run on pages that actually have a posts container
    if (!document.getElementById("postsContainer")) return;

    injectHoverCSS();
    wireNewPost();
    wireSort();

    const sort = document.getElementById("sortPosts");
    displayPosts(sort ? sort.value : "recent");
  });
})();