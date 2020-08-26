<?php

    require "database-backup.php";

    $tables = array(); 
    //$tables = allTables();
    $dbs = getAllDatabase();

    //var_dump($db_name); die();

    
    if(isset($_GET['defSave'])) {
        $db_name = $_GET['defSave'];
    }
    
    if(isset($_GET['sanAffich'])) {
        if(isset($_GET['defSave']) != "") {
            $tables = allTableByName($_GET['defSave']);
            //$db_name = $_GET['defSave'];
        } else {
            $error = "Veuillez séléctionner la Base de données";
        }
    }
    
    if(isset($_GET['table'])){
        $table = $_GET['table'];
        $db_name = $_GET['db_name'];
        getOneTable($db_name, $table);
    }

    if(isset($_GET['allTables'])){
        getAllTables($tables);
    }

    if(isset($_GET['sanSaveTout'])) {
        if(isset($_GET['defSave'])) {
            $tables = allTableByName($_GET['defSave']);
            getAllTables($_GET['defSave'], $tables);
        } else {
            $error = "Veuillez séléctionner la Base de données";
        }
    }

    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" integrity="sha512-UJfAaOlIRtdR+0P6C3KUoTDAxVTuy3lnSXLyLKlHYJlcSU8Juge/mjeaxDNMlw9LgeIotgz5FP8eUQPhX1q10A==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q==" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 

    <!-- <script src="jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="materialize-css/css/materialize.min.css">
    <script src="materialize-css/js/materialize.min.js"></script>
    <link rel="stylesheet" href="material-design-iconic-font/css/material-design-iconic-font.min.css"> -->

    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });
    </script>

<body class="teal lighten-2">
    <div class="container">
        <center> <?= (isset($error))? "<h5 style='background-color: red; color: white; padding: 10px'>" . $error . ' </h5>' : "" ?> </center>
        <h4> Effectuer une sauvegarde </h4>
        <form action="?db_name=<?=$db?>" method="get" >
            <div class="row">
                <div class="input-field col s4">
                    <select name="defSave" id="defSave">
                        <?= (isset($_GET['defSave'])) ? '<option selected value="'.  $_GET['defSave'] .'" class="blue darken-4" > '.  $_GET['defSave'] .' </option>' : '<option value="" disabled selected> CHOISIR LA BD </option>' ?>
                        <?php foreach($dbs as $db): ?>
                            <option value="<?=$db?>" class="blue darken-4" > <?= $db ?> </option>
                        <?php endforeach ?>
                    </select>
                    <label class="white-text" for=""> __ SELECTIONNER LA BASE DE DONNEE __  </label>
                </div>
                <div class="input-field col s8">
                    <button type="submit" class="waves-effect blue waves-light btn" name="sanAffich"> Afficher les tables </button>
                    <button type="submit" class="waves-effect blue waves-light btn" name="sanSaveTout"> Sauvegarder la BD </button>
                </div>
            </div>
        </form>

        <table border="1" class="striped highlight centered">
            <tr>
                <td>Nom Tables</td>
                <td> Taille </td>
                <td> Télécharger </td>
                <!-- <td> <a href="?allTables"> Telecharger la base de données </a> </td> -->
            </tr>
        <?php foreach($tables as $table) : ?>
            <tr>
                <td> <?= $table ?> </td>
                <td> -- </td>
                <td> <a href="?table=<?= $table ?>&db_name=<?= $db_name ?>" class="waves-effect blue waves-light btn"> <i class="material-icons">cloud_download</i> </a> </td>
            </tr>
        <?php endforeach ?>
        </table>
    </div>
</body>