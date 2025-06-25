<?php
/**
 * The template for displaying all single posts
 */
get_header();
$id = get_the_ID();
$page_title = get_the_title($id);
$pdf_journal = get_post_field('pdf_journal', $id);
$journal_see = get_post_field('journal_see', $id);
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
        body.single-journal, .single-post {
            background-color: #333;
        }
        .single-journal .post-head {
            margin-bottom: 0;
        }
        h1 {
            color: #fff;
            text-align: center;
        }
        :root {
            --primary-color: #2e9fff;
            --dark-color: #333;
            --light-color: #f9f9f9;
            --shadow-color: rgba(0, 0, 0, 0.2);
            --text-color: #333;
            --bg-color: #f0f0f0;
            --border-radius: 4px;
        }
        .post-content {
            padding-bottom: 100px;
        }
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            min-height: 74vh;
        }
        .footer {
            margin-top: 0;
        }
        .viewer-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background-color: #333;
            overflow: auto;
        }

        .page-container {
            position: relative;
            box-shadow: 0 5px 15px var(--shadow-color);
            background-color: white;
            transition: transform 0.3s ease;
            margin: 20px;
        }

        .page-number {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
        }

        .controls-container {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 30px;
            padding: 10px 20px;
            opacity: 0.7;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .thumbnails-open .controls-container {
            bottom: 210px;
        }

        .controls-container:hover {
            opacity: 1;
        }

        .control-btn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        .control-btn svg {
            width: 18px;
        }

        .control-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .control-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-slider-container {
            display: flex;
            align-items: center;
            margin: 0 20px;
            width: 300px;
        }

        .page-slider {
            flex: 1;
            -webkit-appearance: none;
            appearance: none;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            outline: none;
        }

        .page-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            background: var(--primary-color);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .page-slider::-webkit-slider-thumb:hover {
            width: 18px;
            height: 18px;
        }

        .page-counter {
            margin-left: 15px;
            color: white;
            font-size: 14px;
            min-width: 60px;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 80px;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            font-size: 24px;
            transition: background-color 0.3s ease;
            z-index: 5;
            opacity: 0.5;
        }

        .nav-arrow:hover {
            background-color: rgba(0, 0, 0, 0.6);
            opacity: 1;
        }

        .nav-prev {
            left: 0;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .nav-next {
            right: 0;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .thumbnails-container {
            display: none;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px;
            height: 180px;
            z-index: 20;
            transition: transform 0.3s ease;
            transform: translateY(100%);
        }

        .thumbnails-container.visible {
            transform: translateY(0);
        }

        .thumbnails-scroll {
            display: flex;
            gap: 10px;
            padding: 10px;
            overflow-x: auto;
            height: 100%;
        }

        .thumbnail {
            height: 120px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.2s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .thumbnail.active {
            border-color: var(--primary-color);
        }

        .thumbnail canvas {
            height: 100%;
            width: auto;
        }

        .zoom-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .zoom-level {
            color: white;
            font-size: 14px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            color: white;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .fullscreen-btn {
            cursor: pointer;
        }

        .logo-text {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            font-size: 24px;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .controls-container {
                flex-wrap: wrap;
                justify-content: center;
                width: 90%;
                padding: 10px;
            }

            .page-slider-container {
                width: 100%;
                order: -1;
                margin-bottom: 10px;
            }

            .zoom-controls {
                margin-top: 10px;
            }

            .top-controls {
                flex-wrap: wrap;
            }

            .nav-arrow {
                width: 40px;
                height: 60px;
            }

            .page-number {
                display: none;
            }

            .page-counter {
                min-width: inherit;
            }
        }
    </style>
    <div class="single-post inner-page">
        <div class="container">
            <div class="inner-container">
                <div class="post-head">
                    <h1><?php print $page_title; ?></h1>
                </div>
            </div>
        </div>
        <div class="post-content">
            <?php
            if (!empty($journal_see)) {
                if (!empty($pdf_journal)) { ?>
                    <div class="main-container">
                        <div class="viewer-container" id="viewer-container">
                            <div class="loading-overlay" id="loading-overlay">
                                <div class="spinner"></div>
                                <div>Завантаження PDF...</div>
                            </div>

                            <div class="page-container" id="page-container"></div>

                            <div class="nav-arrow nav-prev" id="prev-page">&#10094;</div>
                            <div class="nav-arrow nav-next" id="next-page">&#10095;</div>
                        </div>

                        <div class="controls-container">
                            <button id="first-page" class="control-btn" title="Перша сторінка">&#171;</button>
                            <button id="prev-btn" class="control-btn" title="Попередня сторінка">&#8249;</button>

                            <div class="page-slider-container">
                                <input type="range" min="1" max="100" value="1" class="page-slider" id="page-slider">
                                <div class="page-counter" id="page-counter">1 / 100</div>
                            </div>

                            <button id="next-btn" class="control-btn" title="Наступна сторінка">&#8250;</button>
                            <button id="last-page" class="control-btn" title="Остання сторінка">&#187;</button>

                            <div class="zoom-controls">
                                <button id="zoom-out" class="control-btn" title="Зменшити">&#8722;</button>
                                <div class="zoom-level" id="zoom-level">100%</div>
                                <button id="zoom-in" class="control-btn" title="Збільшити">&#43;</button>
                                <button id="fullscreen-btn" class="control-btn fullscreen-btn" title="Повноекранний режим">⛶</button>
                                <button id="thumbnails-toggle" class="control-btn" title="Переглянути сторінки"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M176.792 0H59.208C26.561 0 0 26.561 0 59.208v117.584C0 209.439 26.561 236 59.208 236h117.584C209.439 236 236 209.439 236 176.792V59.208C236 26.561 209.439 0 176.792 0zM196 176.792c0 10.591-8.617 19.208-19.208 19.208H59.208C48.617 196 40 187.383 40 176.792V59.208C40 48.617 48.617 40 59.208 40h117.584C187.383 40 196 48.617 196 59.208v117.584zM452 0H336c-33.084 0-60 26.916-60 60v116c0 33.084 26.916 60 60 60h116c33.084 0 60-26.916 60-60V60c0-33.084-26.916-60-60-60zm20 176c0 11.028-8.972 20-20 20H336c-11.028 0-20-8.972-20-20V60c0-11.028 8.972-20 20-20h116c11.028 0 20 8.972 20 20v116zM176.792 276H59.208C26.561 276 0 302.561 0 335.208v117.584C0 485.439 26.561 512 59.208 512h117.584C209.439 512 236 485.439 236 452.792V335.208C236 302.561 209.439 276 176.792 276zM196 452.792c0 10.591-8.617 19.208-19.208 19.208H59.208C48.617 472 40 463.383 40 452.792V335.208C40 324.617 48.617 316 59.208 316h117.584c10.591 0 19.208 8.617 19.208 19.208v117.584zM452 276H336c-33.084 0-60 26.916-60 60v116c0 33.084 26.916 60 60 60h116c33.084 0 60-26.916 60-60V336c0-33.084-26.916-60-60-60zm20 176c0 11.028-8.972 20-20 20H336c-11.028 0-20-8.972-20-20V336c0-11.028 8.972-20 20-20h116c11.028 0 20 8.972 20 20v116z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path></g></svg></button>
                                <button id="download-btn" class="control-btn" title="Завантажити"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 515.283 515.283" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M400.775 515.283H114.507c-30.584 0-59.339-11.911-80.968-33.54C11.911 460.117 0 431.361 0 400.775v-28.628c0-15.811 12.816-28.628 28.627-28.628s28.627 12.817 28.627 28.628v28.628c0 15.293 5.956 29.67 16.768 40.483 10.815 10.814 25.192 16.771 40.485 16.771h286.268c15.292 0 29.669-5.957 40.483-16.771 10.814-10.815 16.771-25.192 16.771-40.483v-28.628c0-15.811 12.816-28.628 28.626-28.628s28.628 12.817 28.628 28.628v28.628c0 30.584-11.911 59.338-33.54 80.968-21.629 21.629-50.384 33.54-80.968 33.54zM257.641 400.774a28.538 28.538 0 0 1-19.998-8.142l-.002-.002-.057-.056-.016-.016c-.016-.014-.03-.029-.045-.044l-.029-.029a.892.892 0 0 0-.032-.031l-.062-.062-114.508-114.509c-11.179-11.179-11.179-29.305 0-40.485 11.179-11.179 29.306-11.18 40.485 0l65.638 65.638V28.627C229.014 12.816 241.83 0 257.641 0s28.628 12.816 28.628 28.627v274.408l65.637-65.637c11.178-11.179 29.307-11.179 40.485 0 11.179 11.179 11.179 29.306 0 40.485L277.883 392.39l-.062.062-.032.031-.029.029c-.014.016-.03.03-.044.044l-.017.016a1.479 1.479 0 0 1-.056.056l-.002.002c-.315.307-.634.605-.96.895a28.441 28.441 0 0 1-7.89 4.995l-.028.012c-.011.004-.02.01-.031.013a28.5 28.5 0 0 1-11.091 2.229z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path></g></svg></button>
                            </div>
                        </div>

                        <div class="thumbnails-container" id="thumbnails-container">
                            <div class="thumbnails-scroll" id="thumbnails-scroll"></div>
                        </div>
                    </div>
                    <script>
                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

                        const pdfUrl = '<?php print wp_get_attachment_url($pdf_journal); ?>';

                        let pdfDoc = null;
                        let currentPage = 1;
                        let totalPages = 0;
                        let pdfData = null;
                        let zoomLevel = 1.0;

                        const DocumentBody = document.querySelector('body');
                        const viewerContainer = document.getElementById('viewer-container');
                        const pageContainer = document.getElementById('page-container');
                        const pageSlider = document.getElementById('page-slider');
                        const pageCounter = document.getElementById('page-counter');
                        const prevBtn = document.getElementById('prev-btn');
                        const nextBtn = document.getElementById('next-btn');
                        const prevPageArrow = document.getElementById('prev-page');
                        const nextPageArrow = document.getElementById('next-page');
                        const firstPageBtn = document.getElementById('first-page');
                        const lastPageBtn = document.getElementById('last-page');
                        const zoomInBtn = document.getElementById('zoom-in');
                        const zoomOutBtn = document.getElementById('zoom-out');
                        const zoomLevelText = document.getElementById('zoom-level');
                        const thumbnailsToggle = document.getElementById('thumbnails-toggle');
                        const thumbnailsContainer = document.getElementById('thumbnails-container');
                        const thumbnailsScroll = document.getElementById('thumbnails-scroll');
                        const fullscreenBtn = document.getElementById('fullscreen-btn');
                        const downloadBtn = document.getElementById('download-btn');
                        const pdfUpload = document.getElementById('pdf-upload');
                        const loadingOverlay = document.getElementById('loading-overlay');

                        window.addEventListener('load', function() {
                            loadingOverlay.style.display = 'flex';
                            fetch(pdfUrl)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Помилка завантаження PDF');
                                    }
                                    return response.arrayBuffer();
                                })
                                .then(data => {
                                    pdfData = data;
                                    loadPDF(data);
                                })
                                .catch(error => {
                                    console.error('Помилка завантаження PDF:', error);
                                    alert('Помилка завантаження PDF: ' + error.message);
                                    loadingOverlay.style.display = 'none';
                                });
                        });
                        function loadPDF(data) {
                            const loadingTask = pdfjsLib.getDocument({ data });
                            loadingTask.promise.then(function(pdf) {
                                pdfDoc = pdf;
                                totalPages = pdf.numPages;
                                setupUI();
                                createThumbnails();
                                renderPage(currentPage);
                                loadingOverlay.style.display = 'none';
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                                alert('Помилка завантаження PDF: ' + error.message);
                                loadingOverlay.style.display = 'none';
                            });
                        }
                        function setupUI() {
                            pageSlider.max = totalPages;
                            pageSlider.value = 1;
                            pageCounter.textContent = `1 / ${totalPages}`;
                            prevBtn.disabled = true;
                            prevPageArrow.style.opacity = 0.2;
                            firstPageBtn.disabled = true;
                            nextBtn.disabled = totalPages <= 1;
                            nextPageArrow.style.opacity = totalPages <= 1 ? 0.2 : 0.5;
                            lastPageBtn.disabled = totalPages <= 1;
                        }

                        async function renderPage(pageNum) {
                            if (!pdfDoc) return;

                            try {
                                pageContainer.innerHTML = '';

                                const page = await pdfDoc.getPage(pageNum);

                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');

                                const viewport = page.getViewport({ scale: zoomLevel });

                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                pageContainer.style.width = `${viewport.width}px`;
                                pageContainer.style.height = `${viewport.height}px`;

                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                await page.render(renderContext).promise;
                                pageContainer.appendChild(canvas);
                                const pageNumber = document.createElement('div');
                                pageNumber.className = 'page-number';
                                pageNumber.textContent = `${pageNum} / ${totalPages}`;
                                pageContainer.appendChild(pageNumber);
                                currentPage = pageNum;
                                updateUI();

                            } catch (error) {
                                console.error('Error rendering page:', error);
                                pageContainer.innerHTML = `<div style="color: white; padding: 20px;">Помилка відображення сторінки ${pageNum}: ${error.message}</div>`;
                            }
                        }
                        async function createThumbnails() {
                            thumbnailsScroll.innerHTML = '';
                            for (let i = 1; i <= totalPages; i++) {
                                const thumbnail = document.createElement('div');
                                thumbnail.className = 'thumbnail';
                                thumbnail.dataset.page = i;
                                const placeholder = document.createElement('div');
                                placeholder.textContent = i;
                                placeholder.style.display = 'flex';
                                placeholder.style.justifyContent = 'center';
                                placeholder.style.alignItems = 'center';
                                placeholder.style.width = '80px';
                                placeholder.style.height = '120px';
                                placeholder.style.backgroundColor = '#f0f0f0';
                                thumbnail.appendChild(placeholder);
                                thumbnailsScroll.appendChild(thumbnail);
                                renderThumbnail(i, thumbnail);
                                thumbnail.addEventListener('click', function() {
                                    const pageNum = parseInt(this.dataset.page);
                                    renderPage(pageNum);
                                });
                            }
                        }
                        async function renderThumbnail(pageNum, thumbnailElement) {
                            try {
                                const page = await pdfDoc.getPage(pageNum);
                                const viewport = page.getViewport({ scale: 0.2 }); // Small scale for thumbnails

                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                await page.render(renderContext).promise;

                                thumbnailElement.innerHTML = '';
                                thumbnailElement.appendChild(canvas);

                            } catch (error) {
                                console.error(`Error rendering thumbnail for page ${pageNum}:`, error);
                            }
                        }

                        function updateUI() {
                            pageSlider.value = currentPage;
                            pageCounter.textContent = `${currentPage} / ${totalPages}`;
                            prevBtn.disabled = currentPage <= 1;
                            prevPageArrow.style.opacity = currentPage <= 1 ? 0.2 : 0.5;
                            firstPageBtn.disabled = currentPage <= 1;

                            nextBtn.disabled = currentPage >= totalPages;
                            nextPageArrow.style.opacity = currentPage >= totalPages ? 0.2 : 0.5;
                            lastPageBtn.disabled = currentPage >= totalPages;
                            updateThumbnailsSelection();
                        }

                        function updateThumbnailsSelection() {
                            const allThumbnails = document.querySelectorAll('.thumbnail');
                            allThumbnails.forEach(thumb => thumb.classList.remove('active'));

                            const thumbnail = document.querySelector(`.thumbnail[data-page="${currentPage}"]`);
                            if (thumbnail) {
                                thumbnail.classList.add('active');
                                thumbnail.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                            }
                        }

                        function goToPage(pageNum) {
                            if (pageNum < 1 || pageNum > totalPages || pageNum === currentPage) return;
                            renderPage(pageNum);
                        }

                        prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
                        nextBtn.addEventListener('click', () => goToPage(currentPage + 1));
                        prevPageArrow.addEventListener('click', () => goToPage(currentPage - 1));
                        nextPageArrow.addEventListener('click', () => goToPage(currentPage + 1));
                        firstPageBtn.addEventListener('click', () => goToPage(1));
                        lastPageBtn.addEventListener('click', () => goToPage(totalPages));

                        pageSlider.addEventListener('input', function() {
                            goToPage(parseInt(this.value));
                        });

                        zoomInBtn.addEventListener('click', function() {
                            zoomLevel = Math.min(zoomLevel + 0.2, 3.0);
                            zoomLevelText.textContent = `${Math.round(zoomLevel * 100)}%`;
                            renderPage(currentPage);
                        });

                        zoomOutBtn.addEventListener('click', function() {
                            zoomLevel = Math.max(zoomLevel - 0.2, 0.5);
                            zoomLevelText.textContent = `${Math.round(zoomLevel * 100)}%`;
                            renderPage(currentPage);
                        });

                        thumbnailsToggle.addEventListener('click', function() {
                            thumbnailsContainer.style.display = thumbnailsContainer.style.display === 'block' ? 'none' : 'block';
                            thumbnailsContainer.classList.toggle('visible');
                            DocumentBody.classList.toggle('thumbnails-open');
                        });

                        fullscreenBtn.addEventListener('click', function() {
                            if (!document.fullscreenElement) {
                                document.documentElement.requestFullscreen().catch(err => {
                                    console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                                });
                            } else {
                                if (document.exitFullscreen) {
                                    document.exitFullscreen();
                                }
                            }
                        });

                        downloadBtn.addEventListener('click', function() {
                            if (!pdfData) {
                                alert('Спочатку завантажте PDF');
                                return;
                            }

                            const blob = new Blob([pdfData], { type: 'application/pdf' });
                            const url = URL.createObjectURL(blob);

                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'document.pdf';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                        });

                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                                goToPage(currentPage - 1);
                            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                                goToPage(currentPage + 1);
                            } else if (e.key === 'Home') {
                                goToPage(1);
                            } else if (e.key === 'End') {
                                goToPage(totalPages);
                            }
                        });

                        window.addEventListener('resize', function() {
                            if (pdfDoc) {
                                renderPage(currentPage);
                            }
                        });
                    </script>

                <?php } else {
                    print '<div class="no-journal">Вибачте, файл журналу не завантажено.</div>';
                }
            } else {
                print '<div class="no-journal">Цей журнал заборонений для показу адміністратором сайту.</div>';
            }
            ?>
        </div>
    </div>
<?php get_footer(); ?>