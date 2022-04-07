<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <div validate_area="firstForm" style="border: 2px solid black">
            <div>
                <div validate_part="1">
                    <label>ФИО:
                        <input type="text" id="fullName1" name="fullName1" validate_rule="checkFullName" validate_req>
                    </label>
                </div>
                <div validate_part="2">
                    <label>Имя:
                        <input type="text" id="firstName1" name="firstName1" validate_rule="checkFirstName" validate_req>
                    </label>
                </div>
                <div>
                    <label>Фамилия:
                        <input type="text" id="lastName1" name="lastName1" validate_rule="checkLastName" validate_req>
                    </label>
                </div>
                <div>
                    <label>Отчество:
                        <input type="text" id="middleName1" name="middleName1" validate_rule="checkMiddleName">
                    </label>
                </div>
                <div>
                    <label>Дата рождения:
                        <input type="text" id="birthDate1" name="birthDate1" validate_rule="checkBirthDate" validate_req>
                    </label>
                </div>
            </div>
            <button id="1">Отправить</button>
        </div>
        <div validate_area="second_form" style="border: 2px solid black">
            <div validate_part="1" style="border: 1px solid #b7b7b7">
                <div>
                    <label>Имя:
                        <input type="text" id="firstName" name="firstName" validate_rule="checkFirstName" validate_req>
                    </label>
                </div>
                <div>
                    <label>Фамилия:
                        <input type="text" id="lastName" name="lastName" validate_rule="checkLastName" validate_req>
                    </label>
                </div>
                <div>
                    <label>Отчество:
                        <input type="text" id="middleName" name="middleName" validate_rule="checkMiddleName">
                    </label>
                </div>
                <div>
                    <label>Дата рождения:
                        <input type="text" id="birthDateId" name="birthDate" validate_rule="checkBirthDate" validate_req>
                    </label>
                </div>
                <button id="2_1">step 1</button>
            </div>
            <div validate_part="2" style="border: 1px solid #b7b7b7">
                <div>
                    <label>Серия номер пасспорта:
                        <input type="text" id="docSerNum" name="docSerNum" validate_rule="checkPassportSerNum" validate_req validate_autoscan_ignore>
                    </label>
                </div>
                <div>
                    <label>Серия пасспорта:
                        <input type="text" id="docSer" name="docSer" validate_rule="checkPassportSer" validate_req>
                    </label>
                </div>
                <div>
                    <label>Номер пасспорта:
                        <input type="text" id="docNum" name="docNum" validate_rule="checkPassportNum" validate_req>
                    </label>
                </div>
                <div>
                    <label>Дата выдачи:
                        <input type="text" id="docDateIssue" name="docDateIssue" validate_rule="checkPassportIssueDate" validate_dep="birthDateId" validate_req>
                    </label>
                </div>
                <div>
                    <label>Адрес регистрации:
                        <input type="text" id="regPlace" name="regPlace" validate_rule="checkAddress" validate_req>
                    </label>
                </div>
                <div>
                    <label>Кем выдан:
                        <input type="text" id="docIssuer" name="docIssuer" validate_rule="checkPassportIssuer" validate_req>
                    </label>
                </div>
                <button id="2_2">step 2</button>
            </div>
            <div validate_part="3" style="border: 1px solid #b7b7b7">
                <div>
                    <label>Зарплата
                        <input type="checkbox" validate_group="income_type" validate_group_req_someone validate_group_rule="groupIncomeType">
                    </label>
                    <label>Пособие
                        <input type="checkbox" validate_group="income_type" validate_group_req_someone>
                    </label>
                    <label>Другое
                        <input type="checkbox" validate_group="income_type" validate_group_req_someone>
                    </label>
                </div>
                <div>
                    <label>Средний доход:
                        <input type="text" id="averageIncome" name="averageIncome" validate_rule="checkAverageIncome" validate_req>
                    </label>
                </div>
                <div>
                    <label>Средний доход:
                        <input type="text" id="averageIncome1" name="averageIncome" validate_rule="checkAverageIncome" validate_req>
                    </label>
                </div>
                <div>
                    <label>Номер телефона:
                        <input type="text" id="selfPhone" name="selfPhone" validate_rule="checkMobilePhone" validate_req>
                    </label>
                </div>
                <div>
                    <label>Номер телефона контактного лица:
                        <input type="text" id="contactPhone" name="contactPhone" validate_rule="checkMobilePhone">
                    </label>
                </div>
                <div>
                    <label>Секретное слово:
                        <input type="text" id="secret_word" name="secret_word" validate_req>
                    </label>
                </div>
                <div>
                    <label>Кастомный q:
                        <input type="text" id="ccustom" name="ccustom" validate_rule="ccustom">
                    </label>
                </div>
                <div id="addedList23">

                </div>
                <button id="add_2_3">add</button>
                <button id="remove_2_3">remove</button>
                <button id="remove_2_3_all">remove</button>
                <button id="2_3">step 3</button>
            </div>
            <div id="addedList2">

            </div>
            <button id="add_2">add</button>
            <button id="remove_2">remove</button>
            <button id="remove_2_all">remove</button>
            <button id="2">all</button>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>