<div class="container admin-container">
    <div class="admin-header">
        <h1>Product Management</h1>
        <p>Import and Export products via CSV</p>
    </div>

    <?php if (isset($_SESSION['import_summary'])): ?>
        <?php $summary = $_SESSION['import_summary'];
        unset($_SESSION['import_summary']); ?>
        <div class="alert alert-info">
            <h3>Import Summary</h3>
            <ul>
                <li>Success:
                    <?php echo $summary['success']; ?>
                </li>
                <li>Failed:
                    <?php echo $summary['failed']; ?>
                </li>
                <li>Duplicates Updated:
                    <?php echo $summary['duplicates']; ?>
                </li>
            </ul>
            <?php if (!empty($summary['errors'])): ?>
                <div class="errors">
                    <h4>Errors:</h4>
                    <ul>
                        <?php foreach ($summary['errors'] as $error): ?>
                            <li>
                                <?php echo htmlspecialchars($error); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['import_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['import_error'];
            unset($_SESSION['import_error']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-grid">
        <!-- Export Section -->
        <div class="admin-card">
            <div class="card-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
            </div>
            <h2>Export Products</h2>
            <p>Download all products from the database in CSV format.</p>
            <a href="admin/products?action=export" class="admin-btn export-btn">Download CSV</a>
        </div>

        <!-- Import Section -->
        <div class="admin-card">
            <div class="card-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
            </div>
            <h2>Import Products</h2>
            <p>Upload a CSV file to bulk insert or update products.</p>
            <form action="admin/products?action=import" method="POST" enctype="multipart/form-data" class="import-form">
                <div class="file-input-wrapper">
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                    <label for="csv_file" class="file-label">Choose CSV File</label>
                </div>
                <button type="submit" class="admin-btn import-btn">Upload & Import</button>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-container {
        padding: 40px 20px;
        max-width: 1000px;
    }

    .admin-header {
        margin-bottom: 40px;
        text-align: center;
    }

    .admin-header h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .admin-header p {
        color: #666;
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .admin-card {
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .admin-card:hover {
        transform: translateY(-5px);
    }

    .card-icon {
        color: #4361ee;
        margin-bottom: 20px;
    }

    .admin-card h2 {
        margin-bottom: 15px;
        font-size: 1.5rem;
    }

    .admin-card p {
        color: #777;
        margin-bottom: 30px;
    }

    .admin-btn {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }

    .export-btn {
        background: #4cc9f0;
        color: #fff;
    }

    .export-btn:hover {
        background: #3fb8dc;
        box-shadow: 0 5px 15px rgba(76, 201, 240, 0.4);
    }

    .import-btn {
        background: #4361ee;
        color: #fff;
        width: 100%;
    }

    .import-btn:hover {
        background: #3751d4;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }

    .import-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .file-input-wrapper {
        position: relative;
    }

    #csv_file {
        opacity: 0;
        position: absolute;
        width: 1px;
        height: 1px;
    }

    .file-label {
        display: block;
        padding: 10px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .file-label:hover {
        border-color: #4361ee;
    }

    .alert {
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .alert-info {
        background: #e7f0ff;
        border: 1px solid #b6d4fe;
        color: #084298;
    }

    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
    }

    .errors {
        margin-top: 20px;
        font-size: 0.9rem;
        text-align: left;
    }
</style>

<script>
    document.getElementById('csv_file').addEventListener('change', function (e) {
        var fileName = e.target.files[0].name;
        document.querySelector('.file-label').textContent = fileName;
    });
</script>