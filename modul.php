<?php
header('Content-Type: text/html; charset=utf-8');
//var_dump($_POST);
$name = $_POST['modul'];
$xml = '<?xml version="1.0" encoding="utf-8"?>
<extension type="module" version="1.0.0" client="site" method="upgrade">
    <name>mod_'.$_POST['modul'].'</name>
    <author>Irina Inshakova</author>
    <version>1.0.0</version>
    <description>MOD_'.$_POST['modul'].'_XML_DESCRIPTION</description>
    <files>
        <filename>mod_'.$_POST['modul'].'.xml</filename>
        <filename module="mod_'.$_POST['modul'].'">mod_'.$_POST['modul'].'.php</filename>
        <filename>index.html</filename>
        <filename>helper.php</filename>
        <filename>tmpl/default.php</filename>
        <filename>tmpl/index.html</filename>
    </files>
    <config>
    		<fields name="params">
			<fieldset name="basic">
				<field
					name="count"
					type="number"
					label="MOD_ARTICLES_'.$_POST['modul'].'_COUNT_LABEL"
					default="10"
					filter="integer"
					min="1"
					validate="number"
				/>
			</fieldset>
            </fields>
    </config>
</extension>
';

$helper = '<?php
class '.ucfirst($name).'Helper'
{
    /**
     *
     * @param array $params Объект, содержащий параметры модуля
     *
     * @access public
     */    
   public static function set()
    {
        return ; 
    }
} ';

$mod = "<?php 
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php'; 
$hello = ucfirst($name)."Helper::set();
require JModuleHelper::getLayoutPath('mod_".$_POST['modul']."'); 

?>";
$default = '<?php echo $hello; ?>';

if (!empty($_POST['modul'])){ 
mkdir("../tmp/mod_".$_POST['modul']."", 0777);
mkdir("../tmp/mod_".$_POST['modul']."/tmpl", 0777);
$fp = fopen("../tmp/mod_".$_POST['modul']."/mod_".$_POST['modul'].".xml", "w+");//поэтому используем режим 'w'
// записываем данные в открытый файл
fwrite($fp, $xml);
	 
//не забываем закрыть файл, это ВАЖНО
fclose($fp);
$fp = fopen("../tmp/mod_".$_POST['modul']."/index.html", "w+");//поэтому используем режим 'w'
fclose($fp);

$fp = fopen("../tmp/mod_".$_POST['modul']."/mod_".$_POST['modul'].".php", "w+");//поэтому используем режим 'w'
fwrite($fp, $mod);
fclose($fp);

$fp = fopen("../tmp/mod_".$_POST['modul']."/helper.php", "w+");//поэтому используем режим 'w'
fwrite($fp, $helper);
fclose($fp);

//tmpl
$fp = fopen("../tmp/mod_".$_POST['modul']."/tmpl/default.php", "w+");//поэтому используем режим 'w'
fwrite($fp, $default);
fclose($fp);
$fp = fopen("../tmp/mod_".$_POST['modul']."/tmpl/index.html", "w+");//поэтому используем режим 'w'
fclose($fp);
chmod("../tmp/mod_".$_POST['modul']."/mod_".$_POST['modul'].".xml", 0777);  // восьмеричное, верный способ
}
?>


<form method="POST">
<input name="modul" value="" />
<input name="send" type="submit" value="Создать" />
</form>
