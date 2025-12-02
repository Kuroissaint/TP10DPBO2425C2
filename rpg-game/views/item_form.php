<!DOCTYPE html>
<html>
<head>
    <title>Forge New Item</title>
    <link rel="stylesheet" href="css/style.css">
<body>

    <div class="form-container">
        <h2>⚒️ Forge Item</h2>
        
        <form action="index.php?action=store" method="POST">
            
            <label>Item Name</label>
            <input type="text" name="name" required placeholder="Ex: Doombringer Sword">

            <label>Price</label>
            <input type="number" name="price" required placeholder="1000">

            <label>Type</label>
            <select name="type" id="typeSelector" onchange="toggleInputs()">
                <option value="Weapon">Weapon (Senjata)</option>
                <option value="Consumable">Consumable (Potion/Makanan)</option>
                <option value="Accessory">Accessory (Cincin/Kalung)</option> </select>
            </select>

            <div id="weaponInputs">
                <label>Attack Power</label>
                <input type="number" name="attack" placeholder="Damage">
                
                <label>Element</label>
                <input type="text" name="element" placeholder="Fire/Ice/Holy">
            </div>

            <div id="consumableInputs" class="hidden">
                <label>Recover HP Amount</label>
                <input type="number" name="hp" placeholder="0">
                
                <label>Recover Mana Amount</label>
                <input type="number" name="mana" placeholder="0">
            </div>

            <div id="accessoryInputs" class="hidden">
                <label>Bonus STR</label>
                <input type="number" name="str" placeholder="0">
                
                <label>Bonus AGI</label>
                <input type="number" name="agi" placeholder="0">

                <label>Bonus INT</label>
                <input type="number" name="int" placeholder="0">
            </div>

            <button type="submit">Simpan Item</button>
            <br><br>
            <a href="index.php" style="color: #aaa; text-decoration: none;">&laquo; Kembali</a>
        </form>
    </div>

    <script>
        function toggleInputs() {
            let type = document.getElementById('typeSelector').value;
            let weaponDiv = document.getElementById('weaponInputs');
            let consumableDiv = document.getElementById('consumableInputs');
            let accessoryDiv = document.getElementById('accessoryInputs');

            weaponDiv.style.display = 'none';
            consumableDiv.style.display = 'none';
            accessoryDiv.style.display = 'none';
            
            if (type === 'Weapon') {
                weaponDiv.style.display = 'block';
            } else if (type === 'Consumable') {
                consumableDiv.style.display = 'block';
            } else if (type === 'Accessory') {
                accessoryDiv.style.display = 'block';
            }
        }
    </script>
</body>
</html>