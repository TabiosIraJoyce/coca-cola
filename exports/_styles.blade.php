{{-- resources/views/admin/reports/exports/_styles.blade.php --}}
<style>
    /* Page */
    @page { margin: 18mm; }
    body {
        /* Force DOMPDF to scale down everything slightly so wide tables don’t cut */

        zoom: 0.78; /* 78% scaling—adjust if still too wide */
        -moz-transform: scale(0.78);
        -moz-transform-origin: top left;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 10px; /* base - adjusted so A3 fits */
        color: #222;
        -webkit-print-color-adjust: exact;
    }

    /* Header */
    .pdf-header {
        display:flex;
        align-items:center;
        gap:16px;
        margin-bottom:8px;
    }
    .pdf-header img.logo {
        width:120px;
        height:auto;
        object-fit:contain;
    }
    .pdf-meta {
        font-size: 10px;
        line-height:1.05;
    }
    .pdf-title {
        font-size:28px;
        font-weight:800;
        margin:6px 0 10px 0;
    }

    /* Outer box and section title */
    .outer-box {
        border: 3px solid #2B144C;
        border-radius:8px;
        padding:10px;
        margin-bottom:18px;
    }
    .section-title {
        font-size: 22px;
        font-weight:800;
        margin-bottom:8px;
    }

    /* Table styling (consistent across PDFs) */
    table {
        width:100%;
        border-collapse: collapse;
        table-layout: fixed; /* important to auto-shrink columns */
        font-size: 9.5px; /* compact table text */
    }

    thead th {
        background: #7A1812;
        color: #fff;
        padding:6px 6px;
        border: 2px solid #2B144C; /* purple outline */
        text-align:center;
        font-weight:700;
        vertical-align: middle;
    }

    tbody td {
        background: #FCEBCC;
        border: 2px solid #000;
        padding:6px;
        height: 28px;
        text-align:center;
        vertical-align: middle;
        word-wrap: break-word;
        overflow: hidden;
    }

    /* small numeric cells */
    .numeric { text-align: right; padding-right: 8px; }

    /* Make header row slightly smaller to preserve space */
    thead th.small { font-size: 9px; padding:4px; }

    /* force cells to shrink if many columns */
    td, th { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* reduce spacing between outer sections on A3 */
    .outer-box + .outer-box { margin-top: 8px; }

    /* footer note if needed */
    .pdf-footer { font-size: 9px; text-align: right; margin-top:8px; color:#666; }

    /* Keeps layout compact on print */
    @media print {
        body { font-size:9px; }
        .pdf-title { font-size:26px; }
        thead th { font-size:9px; }
        tbody td { font-size:9px; }
    }
</style>
