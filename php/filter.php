<?php
?>

<script src="js/createNewsController.js"></script>
<form onchange="getAllNews();">
    <div>
        Trier par :<br />
        <label>
            <input type="radio" name="sort" value="desc" checked />
            Plus récent d'abord
        </label>
        <label>
            <input type="radio" name="sort" value="asc" />
            Plus vieux d'abord
        </label>
    </div>
    <div>
        <label>Theme :
            <select name="themes" id="themes" onfocus="getAllThemes(true);"></select>
        </label>
    </div>
</form>