<?php
// Database Configuration
$conn = new mysqli("localhost", "root", "");
$sql = "CREATE DATABASE IF NOT EXISTS patient_database";
$conn->query($sql);
$conn->select_db("patient_database");

// Create patients table
$sql = "CREATE TABLE IF NOT EXISTS patients (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT(3) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    category INT(1) NOT NULL,
    score INT(3) NOT NULL,
    diagnosis TEXT NOT NULL,
    test_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Questions Array
$questions = array(
    1 => array(
        "Często czuję się przygniębiony/a lub smutny/a przez większość dnia.",
        "Często odczuwam brak energii i motywacji.",
        "Często stracisz zaintersowanie rzeczami, które wcześniej sprawiały mi radość.",
        "Moje myśli są bardziej nagetywne lub przygnębiające niż zwykle.",
        "Często odczuwam, że twoje emocje są poza twoją kontrolą.",
        "Moje nastroje zmieniają sie szybko i bez wyraźnej przyczyny.",
        "Czasami czuję sięzbyt pobudzony/a lub rozdrażniony/a.",
        "Trudno mi skupić się na codziennyc hobowiązkowach."
    ),
    2 => array(
        "Boję się że mogę stracić kontrlę w sytuacjach społecznych.",
        "Często obawiam sę, że coś złego mi sie przydarzy.",
        "Unikam sytuacji, któe wywołują u mnie lęk,",
        "Mam natrętne mysli, które nie chcą mnie opuścić.",
        "Musze wykonywac pewno czynnosci, aby poczuć się lepiej",
        "Odczuwam nagłe napady strachu lub paniki.",
        "Czesto przejmuję się tym, co inni o mnie myślą.",
        "Czuję sie zdenerwowany/a, gdy jestem zdala od bliskich."
    ),
    3 => array(
        "Mam wrażenie, że ktoś mnie obserwuje lub śledzi.",
        "Słysze lub widzę rzeczy, których inni nie zauważają.",
        "Czasami mam trudności z odróżnieniem rzeczywistości od moich myśli.",
        "Mam silne przekonania, które inni uważają za dziwne.",
        "Trudno mi nawiązać kontakt z innymi ludźmi.",
        "Czasami mam poczucie, że moje myśli są kontrolwane przez kogoś lub coś.",
        "Mam obawy, że inni chcę mi zaszkodzić.",
        "Czuję siewyobcownay/a lub odłączony/a od rzeczywistości."
    )
);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stan psychiczny pacjenta</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff; }
        header { background-color: #8feb52; color: #000000; padding: 10px 0; text-align: center; }
        fieldset { margin: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        legend { text-align: center; font-size: 1.5em; font-weight: bold; border-width: 60%; border: 5px solid #ccc; }
        label { font-weight: bold; line-height: 1.5em; }
        input { margin: 5px; padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
        textarea { margin: 10px; padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin: 5px; padding: 10px 20px; border-radius: 10px; border: 1px black #ccc; background-color: #a5a5a5; color: #000000; cursor: pointer; }
        button:hover { margin: 10px; padding: 10px 20px; border-radius: 5px; border: solid black; background-color: #545554; color: white; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <form id="questions" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Personal Information Fieldset -->
            <fieldset>
                <legend>Dane osobowe</legend>
                <label for="name">Imię i nazwisko:</label>
                <input type="text" id="name" name="name" required><br>
                <label for="age">Wiek:</label>
                <input type="number" id="age" name="age" min="0" max="120" required><br>
                <label for="gender">Płeć:</label>
                <select id="gender" name="gender" required>
                    <option value="">Wybierz płeć</option>
                    <option value="male">Mężczyzna</option>
                    <option value="female">Kobieta</option>
                    <option value="other">Inna</option>
                </select><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="phone">Telefon:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" required><br>
                <label for="address">Adres:</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </fieldset>

            <!-- Category Selection Fieldset -->
            <fieldset>
                <legend>Kategorie objawów</legend>
                <label for="category">Wybierz kategorię:</label>
                <select id="category" name="category">
                    <option value="1" <?php echo (isset($_POST['category']) && $_POST['category'] == '1') ? 'selected' : ''; ?>>Zaburzenia nastroju</option>
                    <option value="2" <?php echo (isset($_POST['category']) && $_POST['category'] == '2') ? 'selected' : ''; ?>>Zaburzenia lękowe</option>
                    <option value="3" <?php echo (isset($_POST['category']) && $_POST['category'] == '3') ? 'selected' : ''; ?>>Zaburzenia psychortyczne</option>
                </select>
            </fieldset>

            <!-- Questions Display -->
            <?php
            $selectedCategory = isset($_POST['category']) ? $_POST['category'] : 1;
            if(isset($questions[$selectedCategory])) {
                foreach($questions[$selectedCategory] as $index => $question) {
                    echo '<div>';
                    echo '<label>' . htmlspecialchars($question) . '</label>';
                    echo '<div>';
                    echo '<label><input type="radio" name="response' . $index . '" value="0"> Wcale</label><br>';
                    echo '<label><input type="radio" name="response' . $index . '" value="1"> Prawie wcale</label><br>';
                    echo '<label><input type="radio" name="response' . $index . '" value="2"> Raczej tak</label><br>';
                    echo '<label><input type="radio" name="response' . $index . '" value="3"> Zdecydowanie tak</label><br>';
                    echo '</div>';
                    echo '</div>';
                }
            }

            // Process Form Submission
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
                $result = 0;
                foreach($_POST as $key => $value) {
                    if(strpos($key, 'response') === 0 && is_numeric($value)) {
                        $result += intval($value);
                    }
                }

                $message = '';
                if(isset($_POST['category'])) {
                    $selectedCategory = $_POST['category'];
                    
                    // Category 1 Results
                    if($selectedCategory == 1) {
                        if($result <= 6) $message = 'Twój wynik sugeruje że masz Depresję.';
                        elseif($result <= 12) $message = 'Twój wynik sugeruje że masz Dystymię.';
                        elseif($result <= 18) $message = 'Twój wynik sugeruje że masz Chorobę afektywnej dwubiegunowości.';
                        elseif($result <= 24) $message = "Twój wynik sugeruje że masz Cyklotymię.";
                    }
                    // Category 2 Results
                    elseif($selectedCategory == 2) {
                        if($result <= 4) $message = 'Twój wynik sugeruje że masz fobię społeczną.';
                        elseif($result <= 8) $message = 'Twój wynik sugeruje że masz zaburzanie obsesyjno-kompusylwne.';
                        elseif($result <= 12) $message = 'Twók wynik sugeruje że masz napady paniki i zaburzenia paniczne';
                        elseif($result <= 16) $message = 'Twój wynik sugeruje że masz fobie społeczne.';
                        elseif($result >= 20) $message = 'Twój wynik sugeruje że masz zespół lępa separacyjnego.';
                    }
                    // Category 3 Results
                    elseif($selectedCategory == 3) {
                        if($result <= 5) $message = 'Twój wynik sugeruje że masz Schizofrenie.';
                        elseif($result <= 10) $message = 'Twój wynik sugeruje że masz zaburzenia schizoaktywne.';
                        elseif($result <= 15) $message = 'Twój wynik sugeruje że masz zespół paranoidalny.';
                        elseif($result >= 20) $message = 'Twój wynik sugeruje że masz zaburzenia urojeniowe.';
                    }
                }
                
                if(!empty($message)) {
                    echo '<div class="result">' . htmlspecialchars($message) . '</div>';
                }

                // Store data in database
                $name = $conn->real_escape_string($_POST['name']);
                $age = $conn->real_escape_string($_POST['age']);
                $gender = $conn->real_escape_string($_POST['gender']);
                $email = $conn->real_escape_string($_POST['email']);
                $phone = $conn->real_escape_string($_POST['phone']);
                $address = $conn->real_escape_string($_POST['address']);
                $category = $conn->real_escape_string($_POST['category']);
                
                $sql = "INSERT INTO patients (name, age, gender, email, phone, address, category, score, diagnosis) 
                        VALUES ('$name', '$age', '$gender', '$email', '$phone', '$address', '$category', '$result', '$message')";
                
                if($conn->query($sql)) {
                    echo '<div class="success">Dane zostały zapisane pomyślnie</div>';
                }
            }
            ?>

            <!-- Form Buttons -->
            <button type="button" onclick="document.querySelectorAll('input[type=\'radio\']:checked').forEach(el => el.checked = false)">Wyczyść odpowiedzi</button>
            <button type="submit">Dalej</button>
        </form>
    </main>
    <script>
        document.getElementById('category').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>