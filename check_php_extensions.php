<?php
echo "<h1>Vérification des extensions PHP</h1>";
echo "<h2>Extensions chargées :</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";

echo "<h2>Extensions spécifiques :</h2>";
$extensions = ['zip', 'mbstring', 'xml', 'gd', 'curl', 'openssl'];

foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<p><strong>$ext</strong>: " . ($loaded ? '<span style="color:green">✓ Chargée</span>' : '<span style="color:red">✗ Non chargée</span>') . "</p>";
}

echo "<h2>Informations système :</h2>";
echo "<p><strong>Version PHP :</strong> " . phpversion() . "</p>";
echo "<p><strong>Système d'exploitation :</strong> " . php_uname() . "</p>";

echo "<h2>Instructions pour activer l'extension zip :</h2>";
echo "<h3>Pour XAMPP (Windows) :</h3>";
echo "<ol>";
echo "<li>Ouvrez le fichier <code>php.ini</code> (généralement dans C:\\xampp\\php\\php.ini)</li>";
echo "<li>Recherchez la ligne <code>;extension=zip</code></li>";
echo "<li>Supprimez le point-virgule au début pour activer l'extension</li>";
echo "<li>Redémarrez Apache dans le panneau de contrôle XAMPP</li>";
echo "</ol>";

echo "<h3>Pour Linux/Ubuntu :</h3>";
echo "<pre>sudo apt-get install php-zip\nsudo systemctl restart apache2</pre>";

echo "<h3>Pour macOS avec Homebrew :</h3>";
echo "<pre>brew install php@8.2\nbrew services restart php@8.2</pre>";

echo "<h3>Alternative :</h3>";
echo "<p>Utilisez le format Excel 97-2003 (.xls) qui ne nécessite pas l'extension zip.</p>";
?>
