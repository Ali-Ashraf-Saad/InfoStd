<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Wed, 11 Jan 1984 05:00:00 GMT");
?>
<!doctype html>
<html lang="ar">
  <head>
    <link rel="icon" href="/favicon.ico?v=2">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>الاستعلام عن المقررات الدراسية</title>

    <!-- مكتبة html2canvas لتصدير الصورة -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
      @import url("https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap");

      * { box-sizing: border-box; }

      body {
        font-family: "Cairo", sans-serif;
        background: linear-gradient(135deg, #0f172a, #020617);
        color: #fff;
        margin: 0; padding: 0;
        display: flex; flex-direction: column; min-height: 100vh;
      }

      .container {
        max-width: 700px; margin: auto; padding: 40px 20px; flex: 1;
      }

      h1 { text-align: center; margin-bottom: 30px; font-weight: 600; }

      .counter { text-align: center; margin-bottom: 20px; color: #94a3b8; font-size: 14px; }

      .search-box { position: relative; }

      input {
        width: 100%; padding: 15px 20px; border-radius: 15px; border: none;
        outline: none; font-size: 16px; background: #1e293b; color: white; transition: 0.3s;
      }

      input:focus { box-shadow: 0 0 15px #3b82f6; }

      /* ---------- سجل البحث ---------- */
      .history {
        margin-top: 12px;
        background: rgba(30, 41, 59, 0.6);
        border-radius: 12px;
        padding: 10px;
        display: none; /* يظهر عند وجود سجل */
      }

      .history-label {
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 8px;
        padding: 0 8px;
      }

      .history-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
      }

      .history-item {
        background: #0f172a;
        border-radius: 8px;
        padding: 4px 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background 0.2s;
      }

      .history-item:hover { background: #1e3a5f; }

      .history-text {
        color: #e2e8f0;
        font-size: 14px;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .history-delete {
        background: none; border: none; color: #94a3b8;
        font-size: 16px; cursor: pointer; padding: 0; line-height: 1;
        transition: color 0.2s;
      }

      .history-delete:hover { color: #f87171; }

      .history-clear {
        text-align: left; color: #f59e0b; font-size: 13px;
        cursor: pointer; padding: 4px 8px; border-radius: 6px;
        transition: background 0.2s;
      }

      .history-clear:hover { background: rgba(245, 158, 11, 0.1); }

      .results { margin-top: 30px; }

      .card {
        background: rgba(30, 41, 59, 0.8); padding: 20px; border-radius: 20px;
        margin-bottom: 20px; animation: fadeIn 0.4s ease; transition: 0.3s;
      }

      .card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
      }

      .name { font-size: 18px; font-weight: 600; }
      .number { color: #94a3b8; margin: 5px 0; }

      .course-item {
        background: #0f172a; border-radius: 12px; padding: 12px;
        margin: 10px 0; border-right: 4px solid #3b82f6;
      }

      .course-name { font-weight: 600; margin-bottom: 6px; }

      .exam-details {
        font-size: 13px; color: #cbd5e1; display: flex; flex-wrap: wrap; gap: 15px;
      }
      .exam-details span { white-space: nowrap; }
      .no-exam { color: #f59e0b; font-style: italic; }
      .no-result { text-align: center; color: #94a3b8; margin-top: 20px; }

      /* زر تصدير الصورة */
      .export-btn-container {
        display: none; /* يظهر عند وجود نتائج */
        text-align: center;
        margin: 20px 0;
      }

      .export-btn {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(59,130,246,0.4);
        display: inline-flex;
        align-items: center;
        gap: 8px;
      }

      .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59,130,246,0.6);
      }

      /* التصميم المخفي للصورة المصدرة */
      #export-card {
        position: absolute;
        left: -9999px;
        top: -9999px;
        width: 600px;
        padding: 30px;
        background: linear-gradient(145deg, #0b1120 0%, #1a1f2f 100%);
        border-radius: 24px;
        color: #e2e8f0;
        font-family: "Cairo", sans-serif;
        direction: rtl;
        border: 2px solid rgba(59,130,246,0.4);
        box-shadow: 0 20px 40px rgba(0,0,0,0.6);
      }

      #export-card .export-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(59,130,246,0.3);
      }

      #export-card .export-name {
        font-size: 26px;
        font-weight: 700;
        background: linear-gradient(to left, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
      }

      #export-card .export-number {
        font-size: 16px;
        color: #94a3b8;
      }

      #export-card .export-course {
        background: rgba(15,23,42,0.8);
        border-radius: 14px;
        padding: 15px;
        margin: 12px 0;
        border-right: 5px solid #3b82f6;
        backdrop-filter: blur(10px);
      }

      #export-card .export-course-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #f1f5f9;
      }

      #export-card .export-exam-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
        color: #cbd5e1;
      }

      #export-card .export-exam-row span {
        background: rgba(59,130,246,0.15);
        padding: 4px 12px;
        border-radius: 20px;
        white-space: nowrap;
      }

      #export-card .no-exam-export {
        color: #f59e0b;
        font-style: italic;
        font-size: 13px;
        margin-top: 5px;
      }

      #export-card .watermark {
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
        color: #475569;
      }

      footer {
        text-align: center; padding: 15px; background: rgba(15, 23, 42, 0.9);
        color: #94a3b8; font-size: 14px;
        border-top: 1px solid rgba(148, 163, 184, 0.2); backdrop-filter: blur(10px);
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      .dashboard-btn {
        position: fixed; top: 20px; left: 20px; display: flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #16537e, #cfe2f3); color: #000; border: none;
        padding: 10px 14px; border-radius: 12px; font-size: 14px; cursor: pointer;
        box-shadow: 0 0 15px rgba(0,200,255,0.4); transition: all 0.3s ease; overflow: hidden;
        z-index: 999;
      }
      .dashboard-btn .text { opacity: 0; max-width: 0; transition: all 0.3s ease; white-space: nowrap; }
      .dashboard-btn:hover { padding: 10px 18px; }
      .dashboard-btn:hover .text { opacity: 1; max-width: 120px; }
      .dashboard-btn:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 0 25px rgba(0,200,255,0.7); }
        .dashboard-btn .icon {
    width: 25px;
    height: 25px;
    margin-right: 8px;
}
    </style>
  </head>

  <body>
    <button class="dashboard-btn" onclick="goDashboard()">
      <img src="dashboard-icon.png" class="icon">
      <span class="text">Dashboard</span>
    </button>

    <div class="container">
      <h1>الاستعلام عن المقررات الدراسية</h1>

      <div class="counter">
        عدد الزوار: <span id="visitCount">...</span>
      </div>

      <div class="search-box">
        <input type="text" id="search" placeholder="اكتب الاسم أو الرقم الأكاديمي..." />
      </div>

      <!-- سجل البحث -->
      <div class="history" id="history">
        <div class="history-label">سجل البحث:</div>
        <div class="history-list" id="history-list"></div>
        <div class="history-clear" id="clear-history">مسح السجل</div>
      </div>

      <!-- زر تصدير الصورة يظهر بعد البحث -->
      <div class="export-btn-container" id="export-container">
        <button class="export-btn" onclick="exportAsImage()">
          📸 تحميل صورة المواد
        </button>
      </div>

      <div class="results" id="results"></div>
    </div>

    <!-- العنصر المخفي للتصدير -->
    <div id="export-card"></div>

    <footer>
      © 2026 StudentsCourses V2 · Developed by Ali Ashraf
    </footer>

    <script>
      // ---------- إدارة السجل ----------
      const HISTORY_KEY = 'search_history';
      const MAX_HISTORY = 10;

      function loadHistory() {
        const raw = localStorage.getItem(HISTORY_KEY);
        return raw ? JSON.parse(raw) : [];
      }

      function saveHistory(history) {
        localStorage.setItem(HISTORY_KEY, JSON.stringify(history));
      }

      function addToHistory(query) {
        if (!query) return;
        let history = loadHistory();
        history = history.filter(item => item !== query);
        history.unshift(query);
        if (history.length > MAX_HISTORY) history.pop();
        saveHistory(history);
        renderHistory();
      }

      function deleteHistoryItem(query) {
        let history = loadHistory().filter(item => item !== query);
        saveHistory(history);
        renderHistory();
      }

      function clearHistory() {
        localStorage.removeItem(HISTORY_KEY);
        renderHistory();
      }

      function renderHistory() {
        const historyContainer = document.getElementById('history-list');
        const historyDiv = document.getElementById('history');
        const history = loadHistory();

        if (history.length === 0) {
          historyDiv.style.display = 'none';
          return;
        }

        historyDiv.style.display = 'block';
        let html = '';
        history.forEach(item => {
          html += `<div class="history-item">
                     <span class="history-text" data-query="${item}">${item}</span>
                     <button class="history-delete" data-query="${item}">×</button>
                   </div>`;
        });
        historyContainer.innerHTML = html;

        document.querySelectorAll('.history-text').forEach(el => {
          el.addEventListener('click', function() {
            const query = this.getAttribute('data-query');
            document.getElementById('search').value = query;
            addToHistory(query);
            lastCommittedQuery = query;
            document.getElementById('search').dispatchEvent(new Event('input'));
          });
        });

        document.querySelectorAll('.history-delete').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const query = this.getAttribute('data-query');
            deleteHistoryItem(query);
          });
        });
      }

      document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'clear-history') {
          clearHistory();
        }
      });

      // ---------- البحث الرئيسي ----------
      const MAX_RESULTS = 20;
      let debounceTimer = null;
      let counted = false;
      let lastCommittedQuery = '';
      let currentStudentData = null; // لتخزين بيانات الطالب الحالي للتصدير

      function loadCounter() {
        fetch("counter.php?action=get")
          .then(res => res.json())
          .then(data => {
            const el = document.getElementById("visitCount");
            if (el) el.innerText = data.count ?? 0;
          })
          .catch(() => {
            const el = document.getElementById("visitCount");
            if (el) el.innerText = "—";
          });
      }
      loadCounter();
      setInterval(loadCounter, 5000);

      const searchInput = document.getElementById("search");

      searchInput.addEventListener("input", function () {
        clearTimeout(debounceTimer);

        const value = this.value.trim();
        const resultsDiv = document.getElementById("results");
        const exportContainer = document.getElementById("export-container");

        if (value === '') {
          if (lastCommittedQuery !== '') {
            addToHistory(lastCommittedQuery);
            lastCommittedQuery = '';
          }
          counted = false;
          resultsDiv.innerHTML = "";
          exportContainer.style.display = 'none';
          currentStudentData = null;
          return;
        }

        debounceTimer = setTimeout(() => {
          if (!counted) {
            counted = true;
            fetch("counter.php?action=increment")
              .then(res => res.json())
              .then(data => {
                const el = document.getElementById("visitCount");
                if (el) el.innerText = data.count;
              });
          }

          fetch("search.php?q=" + encodeURIComponent(value))
            .then(res => res.json())
            .then(data => {
              resultsDiv.innerHTML = "";

              if (!data.results.length) {
                resultsDiv.innerHTML = `<div class="no-result">لا يوجد نتائج</div>`;
                exportContainer.style.display = 'none';
                currentStudentData = null;
              } else {
                const fragment = document.createDocumentFragment();
                // نأخذ أول طالب للتصدير (أو يمكن تخصيصه حسب الحاجة)
                currentStudentData = data.results[0];

                data.results.forEach(item => {
                  const card = document.createElement("div");
                  card.className = "card";

                  let coursesHtml = "";
                  if (item.courses && item.courses.length > 0) {
                    coursesHtml = item.courses.map(course => {
                      let examHtml = "";
                      if (course.exam) {
                        examHtml = `
                          <div class="exam-details">
                            <span>🔢 لجنة ${course.exam.committee}</span>
                            <span>📍 ${course.exam.room}</span>
                            <span>📅 ${course.exam.day} ${course.exam.date}</span>
                            <span>🕒 ${course.exam.period} (${course.exam.time})</span>
                          </div>`;
                      } else {
                        examHtml = `<div class="no-exam">لم تحدد اللجنة بعد</div>`;
                      }

                      const courseTitleHtml = course.driveLink
                        ? `<a href="${course.driveLink}" target="_blank" 
                             style="color:#60a5fa; text-decoration:none;"
                             onmouseover="this.style.textDecoration='underline'"
                             onmouseout="this.style.textDecoration='none'">
                             📘 ${course.name} (${course.code})
                           </a>`
                        : `📘 ${course.name} (${course.code})`;

                      return `
                        <div class="course-item">
                          <div class="course-name">${courseTitleHtml}</div>
                          ${examHtml}
                        </div>`;
                    }).join("");
                  } else {
                    coursesHtml = `<div>لا توجد مواد مسجلة</div>`;
                  }

                  card.innerHTML = `
                    <div class="name">${item.name}</div>
                    <div class="number">الرقم: ${item.number}</div>
                    <div>عدد المواد: ${item.courses.length}</div>
                    ${coursesHtml}`;
                  fragment.appendChild(card);
                });
                resultsDiv.appendChild(fragment);
                exportContainer.style.display = 'block'; // إظهار زر التصدير
              }

              lastCommittedQuery = value;
            });
        }, 300);
      });

      searchInput.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
          const val = this.value.trim();
          if (val) {
            addToHistory(val);
            lastCommittedQuery = val;
            this.blur();
          }
        }
      });

      // ---------- تصدير الصورة ----------
      function exportAsImage() {
        if (!currentStudentData) return;

        const exportCard = document.getElementById('export-card');
        const student = currentStudentData;

        // بناء محتوى البطاقة
        let coursesExportHtml = '';
        student.courses.forEach(course => {
          let examHtml = '';
          if (course.exam) {
            examHtml = `
              <div class="export-exam-row">
                <span>🔢 لجنة ${course.exam.committee}</span>
                <span>📍 ${course.exam.room}</span>
                <span>📅 ${course.exam.day} ${course.exam.date}</span>
                <span>🕒 ${course.exam.period} (${course.exam.time})</span>
              </div>`;
          } else {
            examHtml = `<div class="no-exam-export">لم تحدد اللجنة بعد</div>`;
          }

          coursesExportHtml += `
            <div class="export-course">
              <div class="export-course-name">📘 ${course.name} (${course.code})</div>
              ${examHtml}
            </div>`;
        });

        exportCard.innerHTML = `
          <div class="export-header">
            <div class="export-name">${student.name}</div>
            <div class="export-number">الرقم الأكاديمي: ${student.number}</div>
            <div style="color:#94a3b8; margin-top:5px;">عدد المواد: ${student.courses.length}</div>
          </div>
          ${coursesExportHtml || '<div style="text-align:center; color:#94a3b8;">لا توجد مواد مسجلة</div>'}
          <div class="watermark">تم إنشاؤه بواسطة نظام الاستعلام عن المقررات</div>
        `;

        // استخدام html2canvas لالتقاط الصورة
        html2canvas(exportCard, {
          backgroundColor: null,
          scale: 2, // دقة أعلى
          useCORS: true,
          allowTaint: true
        }).then(canvas => {
          // تحويل canvas إلى رابط تنزيل
          const link = document.createElement('a');
          link.download = `student_${student.number}_courses.png`;
          link.href = canvas.toDataURL('image/png');
          link.click();
        }).catch(err => {
          console.error('فشل في تصدير الصورة:', err);
          alert('حدث خطأ أثناء تصدير الصورة. حاول مرة أخرى.');
        });
      }

      function goDashboard() {
        window.location.href = "dashboard.html";
      }

      renderHistory();
    </script>
  </body>
</html>