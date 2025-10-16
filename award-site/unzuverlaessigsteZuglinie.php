<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBB Railbait Awards</title>
    <link rel="stylesheet" href="../css/award.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/background.css">
</head>
<body>
    <header>
        <a href="../index.php">
            <div class="header-rails"></div>
            <h1>SBB</h1>
            <h2>Railbait Awards</h2>
        </a>
    </header>

    <section>
        <h2 class="award-title">Unzuverlässigste Zuglinien</h2>
        <?php
        // Determine active tab from query parameter, default to 'preistraeger'
        $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'preistraeger';
        ?>

        <div id="tab-container" class="detail_container">
            <div class="category_selection">
                <a class="<?php echo $activeTab === 'aktuell' ? 'detail_active' : 'detail_name'; ?>" href="?tab=aktuell" data-tab="aktuell">Aktuell</a>
                <a class="<?php echo $activeTab === 'rekorde' ? 'detail_active' : 'detail_name'; ?>" href="?tab=rekorde" data-tab="rekorde">Rekorde</a>
                <a class="<?php echo $activeTab === 'preistraeger' ? 'detail_active' : 'detail_name'; ?>" href="?tab=preistraeger" data-tab="preistraeger">Preisträger</a>
            </div>
            <div id="tab-content" style="transition: opacity 0.3s;">
                <?php if ($activeTab === 'aktuell'): ?>
                    <table>
                        <tr>
                            <th class="column_name">Rang</th>
                            <th class="column_name">Zuglinie</th>
                            <th class="column_name">Verspätungen</th>
                        </tr>
                        <tr>
                            <td class="placement">1.</td>
                            <td>S11</td>
                            <td>42</td>
                        </tr>
                        <tr>
                            <td class="placement">2.</td>
                            <td>ICE</td>
                            <td>41</td>
                        </tr>
                        <tr>
                            <td class="placement">3.</td>
                            <td>ICE</td>
                            <td>35</td>
                        </tr>
                        <tr>
                            <td class="placement">4.</td>
                            <td>ICE</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td class="placement">5.</td>
                            <td>ICE</td>
                            <td>28</td>
                        </tr>
                    </table>
                <?php elseif ($activeTab === 'rekorde'): ?>
                    <table>
                        <tr>
                            <th class="column_name">Rang</th>
                            <th class="column_name">Zuglinie</th>
                            <th class="column_name">Verspätungen</th>
                            <th class="column_name">Datum</th>
                        </tr>
                        <tr>
                            <td class="placement">1.</td>
                            <td>S11</td>
                            <td>42</td>
                            <td>12.05.2025</td>
                        </tr>
                        <tr>
                            <td class="placement">2.</td>
                            <td>ICE</td>
                            <td>41</td>
                            <td>12.05.2025</td>
                        </tr>
                        <tr>
                            <td class="placement">3.</td>
                            <td>ICE</td>
                            <td>35</td>
                            <td>12.05.2025</td>
                        </tr>
                        <tr>
                            <td class="placement">4.</td>
                            <td>ICE</td>
                            <td>30</td>
                            <td>12.05.2025</td>
                        </tr>
                        <tr>
                            <td class="placement">5.</td>
                            <td>ICE</td>
                            <td>28</td>
                            <td>12.05.2025</td>
                        </tr>
                    </table>
                <?php else: // preistraeger ?>
                    <table>
                        <tr>
                            <th class="column_name">Rang</th>
                            <th class="column_name">Zuglinie</th>
                            <th class="column_name">Awards</th>
                        </tr>
                        <tr>
                            <td class="placement">1.</td>
                            <td>S11</td>
                            <td>42</td>
                        </tr>
                        <tr>
                            <td class="placement">2.</td>
                            <td>ICE</td>
                            <td>41</td>
                        </tr>
                        <tr>
                            <td class="placement">3.</td>
                            <td>ICE</td>
                            <td>35</td>
                        </tr>
                        <tr>
                            <td class="placement">4.</td>
                            <td>ICE</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td class="placement">5.</td>
                            <td>ICE</td>
                            <td>28</td>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <a class="go-back" href="../index.php">Zurück</a>
    </section>

       <svg id="red-carpet" xmlns="http://www.w3.org/2000/svg" width="1440" height="140" viewBox="0 0 1440 140" fill="none">
    <path d="M234.5 0L-84 171.5H1516L1192 0H234.5Z" fill="url(#paint0_linear_13_39)"/>
    <defs>
    <linearGradient id="paint0_linear_13_39" x1="716" y1="171.5" x2="716" y2="0" gradientUnits="userSpaceOnUse">
    <stop stop-color="#8D1919"/>
    <stop offset="1" stop-color="#420B0B"/>
    </linearGradient>
    </defs>
    </svg>

    <div class="railings-container">
        <div class="railing left"></div>
        <div class="railing right"></div>
    </div>

    <svg class="stage-light left" xmlns="http://www.w3.org/2000/svg" width="1063" height="1024" viewBox="0 0 1063 1024" fill="none">
    <path d="M472.259 1193L-84 -164L1063 1038.84L472.259 1193Z" fill="#F8CC87" fill-opacity="0.2"/>
    </svg>

    <svg class="stage-light right" xmlns="http://www.w3.org/2000/svg" width="1042" height="1024" viewBox="0 0 1042 1024" fill="none">
    <path d="M590.741 1193L1147 -164L0 1038.84L590.741 1193Z" fill="#F8CC87" fill-opacity="0.2"/>
    </svg>

    <script src="../js/table.js"></script>
</body>
</html>