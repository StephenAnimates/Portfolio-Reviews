<ul class="dropdown">
<?PHP
for ($x = 1; $x <= 5; $x++) {
    echo '<li><a href="#" value="'.$x.'">'.get_port_label($x).'</a></li>';
}
?>
</ul>