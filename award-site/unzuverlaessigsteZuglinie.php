<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBB Railbait Awards</title>
    <link rel="stylesheet" href="../css/award.css">
    <!-- <link rel="stylesheet" href="../css/background.css"> -->
</head>
<body>
    <header>
        <div class="header-rails"></div>
        <h1>SBB</h1>
        <h2>Railbait Awards</h2>
    </header>

    <section>
        <h2 class="award-title">Unzuverlässigste Zuglinien</h2>
        <?php
        // Determine active tab from query parameter, default to 'preistraeger'
        $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'aktuell';
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
    <script src="../js/table.js"></script>
</body>
</html>