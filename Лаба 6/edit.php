<?php
declare(strict_types=1);
require __DIR__.'/db.php';
function h(string $v):string{return htmlspecialchars($v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if(!$id||$id<1){http_response_code(400);exit('Некорректный идентификатор записи.');}
$stmt=$pdo->prepare('SELECT * FROM children WHERE id=:id');$stmt->execute([':id'=>$id]);$child=$stmt->fetch();
if(!$child){http_response_code(404);exit('Ребёнок не найден.');}
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 foreach(['full_name','birth_date','group_name','parent_phone','enrollment_date'] as $field)$child[$field]=trim((string)($_POST[$field]??''));
 if(mb_strlen($child['full_name'],'UTF-8')<3||mb_strlen($child['full_name'],'UTF-8')>120)$errors[]='ФИО должно содержать от 3 до 120 символов.';
 if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$child['birth_date'])||strtotime($child['birth_date'])===false)$errors[]='Укажите корректную дату рождения.';
 if($child['group_name']===''||mb_strlen($child['group_name'],'UTF-8')>30)$errors[]='Укажите группу (до 30 символов).';
 if($child['parent_phone']===''||mb_strlen($child['parent_phone'],'UTF-8')>30)$errors[]='Укажите телефон родителя.';
 if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$child['enrollment_date'])||strtotime($child['enrollment_date'])===false)$errors[]='Укажите корректную дату зачисления.';
 if(!$errors){$update=$pdo->prepare('UPDATE children SET full_name=:n,birth_date=:b,group_name=:g,parent_phone=:p,enrollment_date=:e WHERE id=:id');$update->execute([':n'=>$child['full_name'],':b'=>$child['birth_date'],':g'=>$child['group_name'],':p'=>$child['parent_phone'],':e'=>$child['enrollment_date'],':id'=>$id]);header('Location: index.php?updated=1');exit;}
}
?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Редактирование записи</title><link rel="stylesheet" href="style.css"></head><body><header class="hero"><div class="container"><p class="eyebrow">Лабораторная работа №6</p><h1>Редактирование записи</h1></div></header><main class="container"><section class="panel"><h2>Ребёнок №<?=(int)$id?></h2><?php if($errors): ?><div class="errors"><ul><?php foreach($errors as $error): ?><li><?=h($error)?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="form-grid"><label>ФИО ребёнка<input name="full_name" maxlength="120" required value="<?=h($child['full_name'])?>"></label><label>Дата рождения<input type="date" name="birth_date" required value="<?=h($child['birth_date'])?>"></label><label>Группа<input name="group_name" maxlength="30" required value="<?=h($child['group_name'])?>"></label><label>Телефон родителя<input name="parent_phone" maxlength="30" required value="<?=h($child['parent_phone'])?>"></label><label>Дата зачисления<input type="date" name="enrollment_date" required value="<?=h($child['enrollment_date'])?>"></label><div class="form-action"><button class="button" type="submit">Сохранить</button><a class="button secondary" href="index.php">Отмена</a></div></form></section></main></body></html>