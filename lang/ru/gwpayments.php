<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'gwpayments', language 'en', branch 'MOODLE_20_STABLE'
 *
 * File         gwpayments.php
 * Encoding     UTF-8
 *
 * @package     mod_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['promo'] = 'Модуль курса Gateway Payments для Moodle';
$string['promodesc'] = 'Этот плагин написан Sebsoft Managed Hosting & Software Development
(<a href=\'https://www.sebsoft.nl/\' target=\'_new\'>https://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';

$string['amount'] = 'Сумма';

$string['completiondetail:submit'] = 'Сделать платёж';
$string['completionsubmit'] = 'Пользователи должны оплатить этот модуль курса, чтобы он считался завершенным, что дает возможность разблокировать другие ресурсы.';

$string['completiondisabled:label'] = 'Завершение отключено.';
$string['completiondisabled:warning'] = 'Завершение отключено. Этот модуль курса использует завершение курса как способ сделать другие ресурсы доступными.';

$string['contentheader'] = 'Настройки оплаты модуля';

$string['cost'] = 'Стоимость разблокировки';

$string['costduration'] = 'Длительность разблокировки';

$string['costduration_help'] = 'При установке длительности разблокировки это ограничивает состояние завершения заданной длительностью.<br/>
Фактически, это создаст дату истечения срока для разблокированных ресурсов.<br/> Следовательно, этот параметр можно использовать только для разблокировки ресурсов на указанный период времени.<br/>
Значение 0 указывает на отсутствие истечения срока.';
$string['cost_help'] = 'Стоимость разблокировки указывает на оплату, которую необходимо выполнить, прежде чем этот модуль курса будет считаться оплаченным.<br/>
В свою очередь, это отмечает модуль как завершенный, что, в свою очередь, может быть использовано в качестве основы для основных правил завершения, следовательно, разблокировки ресурсов в курсе.';

$string['currency'] = 'Валюта';

$string['err:table:set_sql'] = 'Невозможно установить sql: таблица устанавливает свои собственные параметры.';

$string['err:payment:misconfiguration'] = 'Из-за неправильной конфигурации в настоящее время оплата не может быть произведена.<br/>
Если это сообщение повторяется, сообщите об этом системному администратору.';

$string['err:no-payment-account-set'] = 'Для этого действия не установлен счет оплаты.';
$string['err:payment-account-not-exists'] = 'Платёжный счет для этой активности больше не существует.';
$string['err:payment-account-unavailable'] = 'Платёжный счет для этой активности недоступен.';
$string['err:payment-no-available-gateways'] = 'Для этой активности нет доступных шлюзов. Обычно это происходит, когда ожидаемая валюта недоступна для любого шлюза или один или несколько шлюзов отключены.';

$string['event:order:delivered'] = 'Заказ доставлен';

$string['gwpayments:addinstance'] = 'Добавить новый ресурс Gateway Payments';

$string['gwpayments:view'] = 'Просмотреть модуль';

$string['gwpayments:viewpayments'] = 'Просмотреть обзор платежей';
$string['gwpayments:submitpayment'] = 'Отправить платёж';

$string['pluginname'] = 'Gateway Payments';
$string['modulename'] = 'Gateway Payments';

$string['modulename_help'] = 'Модуль курса Шлюзовые платежи позволяет преподавателю/создателю курса предоставлять ресурс, за который необходимо заплатить, что, в свою очередь, может использоваться для разблокировки доступа к другим ресурсам в курсе на основе завершения действия.
Модуль курса Шлюзовые платежи отмечает завершение действия при оплате, что позволяет использовать этот модуль курса в качестве механизма блокировки для других разделов/модулей курса.';

$string['modulename_link'] = 'mod/gwpayments/view';

$string['modulenameplural'] = 'Платежи';

$string['no-payment-yet'] = 'Вы еще не вносили оплату.';
$string['disablepaymentonmisconfig'] = 'Отключить кнопку оплаты, если невозможно выполнить (действительный) платёж.';
$string['disablepaymentonmisconfig_help'] = 'Если эта опция включена, кнопка оплаты будет отключена, если невозможно выполнить платёж, например, из-за несоответствия валют или отсутствия доступных шлюзов';
$string['notenrolledchoose'] = 'Для выполнения платёжных действий вам необходимо быть зачисленным на этот курс.';
$string['page-mod-gwpayments-x'] = 'Любая страница модуля курса «Платежи через шлюзы»';

$string['paymentaccount'] = 'Платёжный счет';
$string['paymentaccount_help'] = 'Сборы за разблокировку будут перечислены на этот счет.';

$string['pluginadministration'] = 'Администрирование модуля Gateway Payments';
$string['privacy:metadata:database:gwpayments'] = 'Информация о платежах модуля курса Gateway Payments.';
$string['privacy:metadata:database:gwpayments:amount'] = 'Сумма платежа.';
$string['privacy:metadata:database:gwpayments:currency'] = 'Валюта платежа.';
$string['privacy:metadata:database:gwpayments:timecreated'] = 'Время, когда был совершен платёж.';
$string['privacy:metadata:database:gwpayments:timeexpire'] = 'Время, когда платёж будет считаться просроченным.';
$string['privacy:metadata:database:gwpayments:timemodified'] = 'Время последнего обновления платёжной записи.';
$string['privacy:metadata:database:gwpayments:userid'] = 'Пользователь, совершивший платёж.';
$string['sendpaymentbutton'] = 'Выберите тип платежа';

$string['status'] = 'Статус';

$string['status:active'] = 'Активен';

$string['status:expired'] = 'Истёк';

$string['studentdisplayonpayments'] = 'Разрешить просмотр страницы для студентов?';

$string['studentdisplayonpayments_help'] = 'Позволяет студенту получить доступ к странице просмотра модуля после оплаты.
На странице просмотра отображается только информация, связанная с текущим платежом(ами) пользователя.<br/>
Или позволяет получить доступ к странице просмотра, но скрыть после оплаты.';

$string['task:defaulttasks'] = 'Задания по умолчанию.';

$string['timecreated'] = 'Время оплаты';

$string['timemodified'] = 'Время обновления';

$string['timeexpire'] = 'Истекает';

$string['vat'] = 'НДС';

$string['vat_help'] = 'Процент НДС от стоимости курса (примечание: стоимость курса включает НДС).';
$string['showamount'] = 'Показать сумму в таблице платежей';

$string['showallpayments'] = 'Показывать всех кто платил на курсе';

$string['mincosterror'] = 'Стоимость не может быть меньше 0.01';

$string['showduration'] = 'Показать длительность на странице';

$string['sendpaymentsummary'] = 'Нажмите здесь, если кнопка оплаты не работает на вашем устройстве.';

$string['addpaymentlink'] = 'Ссылка на дополнительную страницу оплаты';

$string['addpaymentlink_help'] = 'Если присутствует, открывает раздел с кнопкой для перехода на дополнительную страницу оплаты.';

$string['addpaymentlinkempty'] = 'Не может быть пустым, если платёжный счет отключен.';

$string['hidepaymentaccount'] = 'Использовать только дополнительную ссылку';
$string['hidepaymentaccount_help'] = 'Эта опция заменяет ссылку в кнопке оплаты на ссылку на дополнительную страницу.';
$string['showcost'] = 'Показать стоимость на странице';
$string['currency_help'] = 'Валюта платежа.';
$string['password'] = 'Обходной пароль';
$string['password_help'] = 'Этот пароль может использоваться платёжными модулями для обхода платежа. Если у них есть такая функциональность.';
