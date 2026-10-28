<?php
declare(strict_types=1);
session_start();
$products = [
 'cpu'=>['name'=>'Процессор AMD Ryzen 5','price'=>14500],
 'gpu'=>['name'=>'Видеокарта GeForce RTX 4060','price'=>38900],
 'ram'=>['name'=>'Оперативная память 16 ГБ','price'=>4200],
 'ssd'=>['name'=>'SSD-накопитель 1 ТБ','price'=>6500],
 'monitor'=>['name'=>'Монитор 24 дюйма','price'=>11900],
 'keyboard'=>['name'=>'Клавиатура','price'=>1800],
];
function h(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$errors=[]; $cart=[]; $name=trim((string)($_POST['customer_name']??'')); $email=trim((string)($_POST['customer_email']??''));
$subtotal=0.0; $discount=0.0; $total=0.0; $submitted=false;
if ($_SERVER['REQUEST_METHOD']==='POST') {
 if (mb_strlen($name,'UTF-8')<2) $errors[]='Введите имя (не менее 2 символов).';
 if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Введите корректный email.';
 $quantities=$_POST['quantity']??[];
 foreach($products as $key=>$product) {
  $raw=$quantities[$key]??'0';
  if(!is_scalar($raw)||filter_var((string)$raw,FILTER_VALIDATE_INT)===false){$errors[]='Количество должно быть целым числом.';continue;}
  $quantity=(int)$raw;
  if($quantity<0||$quantity>20){$errors[]='Количество каждого товара должно быть от 0 до 20.';continue;}
  if($quantity>0){$line=$product['price']*$quantity;$cart[]=['name'=>$product['name'],'price'=>$product['price'],'quantity'=>$quantity,'line'=>$line];$subtotal+=$line;}
 }
 if(!$cart)$errors[]='Добавьте в заказ хотя бы один товар.';
 if($subtotal>=50000)$discount=$subtotal*0.10;elseif($subtotal>=25000)$discount=$subtotal*0.05;
 $total=max(0,$subtotal-$discount);$submitted=!$errors;
}
?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Компьютерный магазин — лабораторная №4</title><link rel="stylesheet" href="style.css"></head><body>
<header class="hero"><div class="container"><p class="eyebrow">Лабораторная работа №4 · PHP</p><h1>Компьютерный магазин</h1><p>Выберите комплектующие и рассчитайте учебный заказ.</p></div></header>
<main class="container">
<?php if($submitted): ?><section class="panel success"><h2>Заказ сформирован</h2><p>Покупатель: <strong><?=h($name)?></strong> · Email: <strong><?=h($email)?></strong></p>
<div class="table-wrap"><table><thead><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Сумма</th></tr></thead><tbody>
<?php foreach($cart as $item): ?><tr><td><?=h($item['name'])?></td><td><?=number_format($item['price'],2,',',' ')?> ₽</td><td><?=(int)$item['quantity']?></td><td><?=number_format($item['line'],2,',',' ')?> ₽</td></tr><?php endforeach; ?>
</tbody></table></div><div class="totals"><p>Сумма: <strong><?=number_format($subtotal,2,',',' ')?> ₽</strong></p><p>Скидка: <strong><?=number_format($discount,2,',',' ')?> ₽</strong></p><p class="grand-total">Итого: <?=number_format($total,2,',',' ')?> ₽</p></div><p class="hint">Учебный проект: реальная оплата не выполняется.</p><a class="button" href="index.php">Новый заказ</a></section>
<?php else: ?><section class="panel"><h2>Каталог товаров</h2><p class="hint">Скидка 5% от 25 000 ₽ и 10% от 50 000 ₽.</p>
<?php if($errors): ?><div class="errors"><strong>Проверьте данные:</strong><ul><?php foreach($errors as $error): ?><li><?=h($error)?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post"><div class="form-grid"><label>Имя покупателя<input name="customer_name" minlength="2" maxlength="80" required value="<?=h($name)?>"></label><label>Email<input type="email" name="customer_email" maxlength="120" required value="<?=h($email)?>"></label></div>
<div class="table-wrap"><table><thead><tr><th>Комплектующая</th><th>Цена</th><th>Количество (0–20)</th></tr></thead><tbody>
<?php foreach($products as $key=>$product): ?><tr><td><?=h($product['name'])?></td><td><?=number_format($product['price'],2,',',' ')?> ₽</td><td><input class="quantity" type="number" name="quantity[<?=h($key)?>]" min="0" max="20" step="1" value="<?=h((string)($_POST['quantity'][$key]??'0'))?>"></td></tr><?php endforeach; ?>
</tbody></table></div><button class="button" type="submit">Рассчитать заказ</button></form></section><?php endif; ?>
<footer class="footer">Учебная работа по веб-программированию на PHP.</footer></main></body></html>