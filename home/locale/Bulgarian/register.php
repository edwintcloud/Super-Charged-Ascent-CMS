<?php
$locale['400'] = "�����������";
$locale['401'] = "����������";
// Registration Errors
$locale['402'] = "������ ������������� ���, �-���� � ������.";
$locale['403'] = "�������������� ��� � ���������.";
$locale['404'] = "����� ������ �� ��������.";
$locale['405'] = "��������� ������, ���������� �� ���� ����� � �����.<br>
�������� ������ �� � ���� 6 �������.";
$locale['406'] = "�-����� �� � ���������.";
$locale['407'] = "��������������� ��� ".(isset($_POST['username']) ? $_POST['username'] : "")." � �����.";
$locale['408'] = "�-����� ".(isset($_POST['email']) ? $_POST['email'] : "")." � ����.";
$locale['409'] = "��� ��������� ������ � ���� �-����.";
$locale['410'] = "��������� ������������ ���.";
$locale['411'] = "�-����� �� � ��������.";
// Email Message
$locale['449'] = "����� ����� � ".$settings['sitename'];
$locale['450'] = "������� ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
����� �����/����� � ".$settings['sitename'].". ��� ��������� �� ���� ����������:\n
������������� ���: ".(isset($_POST['username']) ? $_POST['username'] : "")."
������: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
���� ����������� ������� �� ��� ������� ����:\n";
// Registration Success/Fail
$locale['451'] = "������������� � ���������";
$locale['452'] = "���� ����� �� ������ � ����������� ��.";
$locale['453'] = "�� ������ �� ������� ������������� �� ������ ������������� ��.";
$locale['454'] = "�� ������� ��������� ���� �� ������������ �� ����.";
$locale['455'] = "������� �� �� ��������.";
$locale['456'] = "������������� � ���������";
$locale['457'] = "����������� �� ����� �� ���������, ���� �������� �� � <a href='mailto:".$settings['siteemail']."'>����</a>.";
$locale['458'] = "�������� �������� ���������:";
$locale['459'] = "���� �������� ������";
// Register Form
$locale['500'] = "���� �������� ��������� ������. ";
$locale['501'] = "��������� ���� �� ���� �������� �� ������������ �� ����. ";
$locale['502'] = "������ ��������� � <span style='color:#ff0000;'>*</span> �� ������������.
�� �������� � ���� ���������� ������������� ��� � ������ �� � ������ ��� ����� �����, ���� �� ���������� � Caps Lock.";
$locale['503'] = " ������ �� �������� ������ ���������� �� ��� ���� ������ �����.";
$locale['504'] = "������������ ���:";
$locale['505'] = "�������� ����:";
$locale['506'] = "�����������";
$locale['507'] = "������������� � ������� �� ����������.";
// Validation Errors
$locale['550'] = "���� �������� ������������� ���.";
$locale['551'] = "���� �������� ������.";
$locale['552'] = "���� �������� �-����.";
?>