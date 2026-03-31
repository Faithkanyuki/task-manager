<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager API — Faith Kanyuki</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --black: #0a0a0a;
            --white: #f5f0e8;
            --accent: #ff4d00;
            --accent2: #00c896;
            --muted: #888;
            --card: #141414;
            --border: #222;
        }

        body {
            background: var(--black);
            color: var(--white);
            font-family: 'Syne', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background grid */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,77,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,77,0,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(60px); }
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 80px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.1s;
        }

        .logo {
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            color: var(--accent);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .badge {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--accent2);
            border: 1px solid var(--accent2);
            padding: 4px 12px;
            border-radius: 100px;
            letter-spacing: 1px;
        }

        /* Hero */
        .hero {
            margin-bottom: 70px;
        }

        .eyebrow {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.2s;
        }

        .hero-title {
            font-size: clamp(42px, 8vw, 82px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 8px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.3s;
        }

        .hero-title span {
            color: var(--accent);
        }

        .hero-subtitle {
            font-size: clamp(32px, 6vw, 62px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            color: var(--muted);
            margin-bottom: 32px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.4s;
        }

        .hero-desc {
            font-size: 16px;
            color: #aaa;
            line-height: 1.7;
            max-width: 520px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.5s;
        }

        .author-line {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.6s;
        }

        .author-dot {
            width: 8px;
            height: 8px;
            background: var(--accent2);
            border-radius: 50%;
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.4); }
        }

        .author-name {
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            color: var(--accent2);
            letter-spacing: 1px;
        }

        /* Base URL card */
        .url-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            padding: 20px 24px;
            border-radius: 4px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.7s;
        }

        .url-label {
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            color: var(--accent);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .url-text {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            color: var(--white);
            word-break: break-all;
        }

        .copy-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 8px 18px;
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            letter-spacing: 1px;
            cursor: pointer;
            border-radius: 3px;
            transition: opacity 0.2s;
            white-space: nowrap;
        }

        .copy-btn:hover { opacity: 0.8; }

        /* Endpoints */
        .section-label {
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.8s;
        }

        .endpoints {
            display: grid;
            gap: 10px;
            margin-bottom: 50px;
        }

        .endpoint {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            opacity: 0;
            animation: fadeUp 0.5s ease forwards;
            transition: border-color 0.2s, transform 0.2s;
            cursor: default;
        }

        .endpoint:hover {
            border-color: #333;
            transform: translateX(4px);
        }

        .endpoint:nth-child(1) { animation-delay: 0.85s; }
        .endpoint:nth-child(2) { animation-delay: 0.92s; }
        .endpoint:nth-child(3) { animation-delay: 0.99s; }
        .endpoint:nth-child(4) { animation-delay: 1.06s; }
        .endpoint:nth-child(5) { animation-delay: 1.13s; }

        .method {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 3px;
            min-width: 64px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .method.get    { background: rgba(0,200,150,0.12); color: var(--accent2); }
        .method.post   { background: rgba(255,200,0,0.12); color: #ffc800; }
        .method.patch  { background: rgba(100,150,255,0.12); color: #6496ff; }
        .method.delete { background: rgba(255,77,0,0.12); color: var(--accent); }

        .endpoint-path {
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            color: var(--white);
            flex: 1;
        }

        .endpoint-desc {
            font-size: 13px;
            color: var(--muted);
            text-align: right;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border);
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: gap;
            gap: 16px;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 1.2s;
        }

        .footer-left {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--muted);
        }

        .footer-left span { color: var(--accent); }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--white); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .endpoint-desc { display: none; }
            .header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .url-card { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
<div class="container">

    <header class="header">
        <div class="logo">// Task Manager API</div>
        <div class="badge">● LIVE</div>
    </header>

    <section class="hero">
        <p class="eyebrow">Cytonn — Software Engineering Internship Challenge 2026</p>
        <h1 class="hero-title">Task <span>Manager</span></h1>
        <h2 class="hero-subtitle">REST API</h2>
        <p class="hero-desc">
            A fully functional Task Management API built with Laravel 13 and MySQL.
            Supports task creation, listing, status progression, deletion, and daily reporting.
        </p>
        <div class="author-line">
            <div class="author-dot"></div>
            <span class="author-name">Built by Faith Kanyuki</span>
        </div>
    </section>

    <div class="url-card">
        <div>
            <div class="url-label">Base URL</div>
            <div class="url-text" id="baseUrl">https://task-manager-production-e95f.up.railway.app</div>
        </div>
        <button class="copy-btn" onclick="copyUrl()">COPY URL</button>
    </div>

    <p class="section-label">Available Endpoints</p>

    <div class="endpoints">
        <div class="endpoint">
            <span class="method post">POST</span>
            <span class="endpoint-path">/api/tasks</span>
            <span class="endpoint-desc">Create a task</span>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="endpoint-path">/api/tasks</span>
            <span class="endpoint-desc">List all tasks</span>
        </div>
        <div class="endpoint">
            <span class="method patch">PATCH</span>
            <span class="endpoint-path">/api/tasks/{id}/status</span>
            <span class="endpoint-desc">Update task status</span>
        </div>
        <div class="endpoint">
            <span class="method delete">DELETE</span>
            <span class="endpoint-path">/api/tasks/{id}</span>
            <span class="endpoint-desc">Delete a task</span>
        </div>
        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="endpoint-path">/api/tasks/report?date=YYYY-MM-DD</span>
            <span class="endpoint-desc">Daily report</span>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-left">
            Laravel <span>v13</span> · MySQL <span>8.4</span> · PHP <span>8.3</span>
        </div>
        <div class="footer-links">
            <a href="https://github.com/Faithkanyuki/task-manager" target="_blank">GITHUB</a>
            <a href="/api/tasks" target="_blank">TEST API</a>
        </div>
    </footer>

</div>

<script>
    function copyUrl() {
        const url = document.getElementById('baseUrl').textContent;
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.querySelector('.copy-btn');
            btn.textContent = 'COPIED!';
            btn.style.background = '#00c896';
            setTimeout(() => {
                btn.textContent = 'COPY URL';
                btn.style.background = '#ff4d00';
            }, 2000);
        });
    }
</script>
</body>
</html>