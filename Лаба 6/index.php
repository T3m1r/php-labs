<?php
declare(strict_types=1);
require __DIR__.'/db.php';
function h(string $v):string{return htmlspecialchars($v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
$errors=[];$fullName=$birthDate=$groupName=$parentPhone=$enrollmentDate='';
$allowedSorts=['full_name','birth_date','group_name','enrollment_date'];$sort=(string)($_GET['sort']??'full_name');
if(!in_array($sort,$allowedSorts,true))$sort='full_name';$direction=strtoupper((string)($_GET['direction']??'ASC'))==='DESC'?'DESC':'ASC';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $fullName=trim((string)($_POST['full_name']??''));$birthDate=trim((string)($_POST['birth_date']??''));$groupName=trim((string)($_POST['group_name']??''));$parentPhone=trim((string)($_POST['parent_phone']??''));$enrollmentDate=trim((string)($_POST['enrollment_date']??''));
 if(mb_strlen($fullName,'UTF-8')<3||mb_strlen($fullName,'UTF-8')>120)$errors[]='ФИО должно содержать от 3 до 120 символов.';
 if(!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/',$birthDate)||strtotime($birthDate)===false)$errors[]='Укажите корректную дату рождения.';
 if($groupName===''||mb_strlen($groupName,'UTF-8')>30)$errors[]='Укажите группу (до 30 символов).';
 if($parentPhone===''||mb_strlen($parentPhone,'UTF-8')>30)$errors[]='Укажите телефон родителя (до 30 символов).';
 if(!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/',$enrollmentDate)||strtotime($enrollmentDate)===false)$errors[]='Укажите корректную дату зачисления.';
 if(!$errors){$stmt=$pdo->prepare('INSERT INTO children (full_name,birth_date,group_name,parent_phone,enrollment_date) VALUES (:n,:b,:g,:p,:e)');$stmt->execute([':n'=>$fullName,':b'=>$birthDate,':g'=>$groupName,':p'=>$parentPhone,':e'=>$enrollmentDate]);header('Location: index.php?added=1');exit;}
}
$children=$pdo->query("SELECT id,full_name,birth_date,group_name,parent_phone,enrollment_date FROM children ORDER BY {$sort} {$direction}")->fetchAll();
function sortLink(string $field,string $sort,string $direction):string{$next=$sort===$field&&$direction==='ASC'?'DESC':'ASC';return '?sort='.rawurlencode($field).'&direction='.$next;}
?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Учёт детей — лабораторная №6</title><link rel="stylesheet" href="style.css"></head><body><header class="hero"><div class="container"><p class="eyebrow">Лабораторная работа №6 · PHP + MySQL</p><h1>Учёт детей в детском саду</h1></div></header><main class="container">
<?php if(isset($_GET['added'])): ?><p class="success">Запись успешно добавлена.</p><?php endif; ?>
<section class="panel"><h2>Добавить ребёнка</h2><?php if($errors): ?><div class="errors"><ul><?php foreach($errors as $error): ?><li><?=h($error)?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="form-grid"><label>ФИО ребёнка<input name="full_name" maxlength="120" required value="<?=h($fullName)?>"></label><label>Дата рождения<input type="date" name="birth_date" required value="<?=h($birthDate)?>"></label><label>Группа<input name="group_name" maxlength="30" required value="<?=h($groupName)?>"></label><label>Телефон родителя<input name="parent_phone" maxlength="30" required value="<?=h($parentPhone)?>"></label><label>Дата зачисления<input type="date" name="enrollment_date" required value="<?=h($enrollmentDate)?>"></label><div class="form-action"><button class="button" type="submit">Добавить запись</button></div></form></section>
<section class="panel"><h2>Список детей</h2><p class="hint">Нажмите заголовок столбца, чтобы изменить сортировку.</p><div class="table-wrap"><table><thead><tr><th>ID</th><th><a href="<?=h(sortLink('full_name',$sort,$direction))?>">ФИО</a></th><th><a href="<?=h(sortLink('birth_date',$sort,$direction))?>">Дата рождения</a></th><th><a href="<?=h(sortLink('group_name',$sort,$direction))?>">Группа</a></th><th>Телефон родителя</th><th><a href="<?=h(sortLink('enrollment_date',$sort,$direction))?>">Зачислен</a></th><th>Действие</th></tr></thead><tbody>
<?php if(!$children): ?><tr><td colspan="7">Записей пока нет.</td></tr><?php endif; ?>
<?php foreach($children as $child): ?><tr><td><?=(int)$child['id']?></td><td><?=h($child['full_name'])?></td><td><?=h($child['birth_date'])?></td><td><?=h($child['group_name'])?></td><td><?=h($child['parent_phone'])?></td><td><?=h($child['enrollment_date'])?></td><td><a href="edit.php?id=<?=(int)$child['id']?>">Изменить</a></td></tr><?php endforeach; ?>
</tbody></table></div><p class="hint">Всего записей: <?=count($children)?>.</p></section></main></body></html>