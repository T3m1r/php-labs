<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';
$visitFile=__DIR__.'/visits.txt';$visits=is_file($visitFile)?(int)trim((string)file_get_contents($visitFile)):0;$visits++;
file_put_contents($visitFile,(string)$visits,LOCK_EX);
$letter=mb_strtoupper(trim((string)($_GET['letter']??'')),'UTF-8');$selected=trim((string)($_GET['city']??''));$matches=[];
foreach($cities as $key=>$item){if($letter===''||mb_strtoupper(mb_substr($item['name'],0,1,'UTF-8'),'UTF-8')===$letter)$matches[$key]=$item;}
$city=$selected!==''&&isset($cities[$selected])?$cities[$selected]:null;
function h(string $v):string{return htmlspecialchars($v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Справочник городов Канады — лабораторная №5</title><link rel="stylesheet" href="style.css"></head><body>
<header class="hero"><div class="container"><p class="eyebrow">Лабораторная работа №5 · PHP</p><h1>Справочник городов Канады</h1><p>Поиск по первой букве и изучение строковых функций PHP.</p></div></header><main class="container">
<section class="panel"><h2>Поиск города</h2><form method="get" class="search"><label for="letter">Первая буква названия</label><select id="letter" name="letter"><option value="">Все города</option>
<?php foreach(array_unique(array_map(fn($c)=>mb_strtoupper(mb_substr($c['name'],0,1,'UTF-8'),'UTF-8'),$cities)) as $char): ?><option value="<?=h($char)?>" <?=$letter===$char?'selected':''?>><?=h($char)?></option><?php endforeach; ?></select><button class="button" type="submit">Найти</button></form>
<div class="city-list"><?php foreach($matches as $key=>$item): ?><a href="?city=<?=h($key)?>"><?=h($item['name'])?></a><?php endforeach; ?></div></section>
<?php if($city): ?><section class="panel"><h2><?=h($city['name'])?></h2><p><strong>Провинция:</strong> <?=h($city['province'])?></p><p><strong>Описание:</strong> <?=h($city['description'])?></p><h3>Строковые функции</h3><ul><li>Длина названия: <?=mb_strlen($city['name'],'UTF-8')?> символов.</li><li>Первая буква: <?=h(mb_substr($city['name'],0,1,'UTF-8'))?>.</li><li>Позиция строки «<?=h($city['search'])?>»: <?php $pos=mb_stripos($city['name'],$city['search'],0,'UTF-8');echo $pos===false?'не найдена':(string)$pos;?>.</li></ul></section><?php endif; ?>
<section class="panel"><h2>Список городов</h2><p>Найдено: <?=count($matches)?>.</p><div class="table-wrap"><table><thead><tr><th>Город</th><th>Провинция</th><th>Открыть</th></tr></thead><tbody><?php foreach($matches as $key=>$item): ?><tr><td><?=h($item['name'])?></td><td><?=h($item['province'])?></td><td><a href="?city=<?=h($key)?>">Подробнее</a></td></tr><?php endforeach; ?></tbody></table></div><p class="hint">Посещений страницы: <?=$visits?> (счётчик хранится в visits.txt).</p></section><footer class="footer">Учебный справочник. Данные приведены для демонстрации PHP.</footer></main></body></html>