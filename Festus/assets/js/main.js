document.addEventListener('DOMContentLoaded', function () {
  const counters = document.querySelectorAll('[data-count]');
  const duration = 2000;

  counters.forEach((counter) => {
    const target = parseInt(counter.dataset.count, 10);
    const step = Math.ceil(target / (duration / 60));
    let current = 0;

    const update = () => {
      current += step;
      if (current >= target) {
        counter.textContent = target.toLocaleString();
      } else {
        counter.textContent = current.toLocaleString();
        requestAnimationFrame(update);
      }
    };

    update();
  });

  const uploadDropZone = document.querySelector('.upload-dropzone');
  if (uploadDropZone) {
    const fileInput = uploadDropZone.querySelector('input[type=file]');
    const status = uploadDropZone.querySelector('.upload-status');

    const updateLabel = (files) => {
      const count = files.length;
      status.textContent = count > 0 ? `${count} file${count > 1 ? 's' : ''} selected` : 'Drag & drop files here or click to upload';
    };

    uploadDropZone.addEventListener('click', () => fileInput.click());
    uploadDropZone.addEventListener('dragover', (event) => {
      event.preventDefault();
      uploadDropZone.classList.add('drag-over');
    });
    uploadDropZone.addEventListener('dragleave', () => {
      uploadDropZone.classList.remove('drag-over');
    });
    uploadDropZone.addEventListener('drop', (event) => {
      event.preventDefault();
      uploadDropZone.classList.remove('drag-over');
      fileInput.files = event.dataTransfer.files;
      updateLabel(fileInput.files);
    });
    fileInput.addEventListener('change', () => updateLabel(fileInput.files));
  }

  const charts = document.querySelectorAll('.chart-placeholder');
  charts.forEach((canvas) => {
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    const chartType = canvas.dataset.chart || 'bar';
    new Chart(ctx, {
      type: chartType,
      data: {
        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
        datasets: [{
          label: canvas.dataset.label || 'Trend',
          data: [34, 58, 76, 92],
          backgroundColor: 'rgba(0, 107, 63, 0.64)',
          borderColor: 'rgba(0, 107, 63, 0.95)',
          borderWidth: 2,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false }
        },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, grid: { color: 'rgba(15, 23, 42, 0.08)' } }
        }
      }
    });
  });

  const mobileLang = document.querySelector('.language-selector');
  if (mobileLang) {
    mobileLang.addEventListener('change', function () {
      alert(`Language changed to ${this.value}.`);
    });
  }
});
