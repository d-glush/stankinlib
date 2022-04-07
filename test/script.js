class Validator {
  config = {
    noPartFieldsPartName: '__default__',
    attr_validate_area: 'validate_area',
    attr_validate_part: 'validate_part',
    attr_validate_rule: 'validate_rule',
    attr_validate_dep: 'attr_validate_dep',
    attr_validate_req: 'validate_req',
    attr_validate_group: 'validate_group',
    attr_validate_group_rule: 'validate_group_rule',
    attr_validate_group_req_one: 'validate_group_req_one',
    attr_validate_group_req_someone: 'validate_group_req_someone',
    attr_validate_autoscan_ignore: 'validate_autoscan_ignore',
    validate_error_req: 'Обязательно к заполнению',
  }

  validatorMethods;
  areaName;
  areaNode;
  parts;

  constructor(validateAreaName, customConfig) {
    for (const configKey in customConfig) {
      this.config.configKey = customConfig.configKey
    }
    this.validatorMethods = new ValidatorMethods();
    this.reset(validateAreaName);
  }

  reset(validateAreaName = this.areaName) {
    this.areaName = validateAreaName
    this.areaNode = document.querySelector(`[${this.config.attr_validate_area}="${validateAreaName}"]`)
    this.parts = {};
    this.__resetNoPart();
    this.__resetParts();
  }

  addField(fieldNode, partName = this.config.noPartFieldsPartName) {
    console.log(partName)
    this.parts[partName].fields[fieldNode.id] = this.__collectFieldData(fieldNode);
  }

  removeField(fieldNode, partName = '') {
    let fieldList = this.__getFieldListById(fieldNode.id, partName)
    let fieldId = fieldNode.id;
    delete this.parts[partName].fields[fieldId];
  }

  addAllFieldsInNode(node, partName = '') {
    let addingFields = this.__findAllFieldsInNode(node, false);
    let currentFieldList = this.__getFieldListById(partName, partName);
    addingFields.forEach((fieldNode) => {
      let fieldId = fieldNode.id;
      currentFieldList[fieldId] = this.__collectFieldData(fieldNode);
    })
  }

  removeAllFieldsInNode(node, partName = '') {
    let removingFields = this.__findAllFieldsInNode(node, false);
    let currentFieldList = this.__getFieldListById(partName, partName);
    let removedFields = [];
    removingFields.forEach((fieldNode) => {
      let fieldId = fieldNode.id;
      if (currentFieldList[fieldId]) {
        removedFields[fieldId] = fieldNode;
        delete currentFieldList[fieldId];
      }
    })
    return removedFields;
  }

  setFieldReq(fieldNode, partName = '') {
    if (fieldNode.getAttribute(this.config.attr_validate_req) === null) {
      this.__toggleFieldReq(fieldNode, partName);
    }
  }

  unsetFieldReq(fieldNode, partName = '') {
    if (!(fieldNode.getAttribute(this.config.attr_validate_req) === null)) {
      this.__toggleFieldReq(fieldNode, partName);
    }
  }

  addCustomValidateMethod(methodName, callable) {
    this.validatorMethods[methodName] = callable;
  }

  validateAll() {
    let result = this.__validateNoPartFields();
    for (const partName in this.parts) {
      let partValidationResult = this.validatePart(partName)
      for (const partValidationResultKey in partValidationResult) {
        result[partValidationResultKey] = partValidationResult[partValidationResultKey]
      }
    }
    return result;
  }

  validatePart(partName) {
    if (this.parts[partName] && this.parts[partName].fields) {
      return this.__validateFields(this.parts[partName].fields)
    }
    return [];
  }

  __getFieldListById(fieldId, partName = '') {
    if (partName === '') {
      for (const partName in this.parts) {
        let fieldList = this.parts[partName];
        let fieldIds = Object.keys(fieldList);
        if (fieldIds.includes(fieldId)) {
          return fieldList;
        }
      }
    } else {
      return this.parts[partName].fields;
    }
  }

  __collectFieldData(fieldNode) {
    let rulesAttr = fieldNode.getAttribute(this.config.attr_validate_rule);
    let validateRules = rulesAttr ? rulesAttr.split(',') : [];
    let id = fieldNode.id;
    let isRequired = fieldNode.getAttribute(this.config.attr_validate_req) !== null;
    let depsAttr = fieldNode.getAttribute(this.config.attr_validate_dep);
    let dependencies = depsAttr ? depsAttr.split(',') : [];
    return {
      node: fieldNode,
      id: id,
      isRequired: isRequired,
      dependencies: dependencies,
      validateRules: validateRules
    };
  }

  __resetNoPart() {
    let noPartFields = this.__findNoPartFields();
    this.parts[this.config.noPartFieldsPartName] = {fields: {}};
    for (const noPartFieldId in noPartFields) {
      let noPartField = noPartFields[noPartFieldId];
      let id = noPartField.id;
      this.parts[this.config.noPartFieldsPartName].fields[id] = this.__collectFieldData(noPartField)
    }
  }

  __resetParts() {
    let validateParts = this.areaNode.querySelectorAll(`[${this.config.attr_validate_part}]`)
    for (const validatePart of validateParts) {
      let partName = validatePart.getAttribute(this.config.attr_validate_part);
      this.parts[partName] = {node: validatePart, fields: {}};
      this.__resetPart(partName)
    }
  }

  __resetPart(partName = this.config.noPartFieldsPartName) {
    if (partName === this.config.noPartFieldsPartName) {
      this.__resetNoPart();
      return;
    }
    let partNode = this.parts[partName].node;
    let validateFields = this.__findAllFieldsInNode(partNode);
    for (const validateField of validateFields) {
      let id = validateField.id;
      this.parts[partName].fields[id] = this.__collectFieldData(validateField)
    }
  }

  __findAllFieldsInNode(node, isIgnoreEnable = true) {
    let allFieldsByRule = [...node.querySelectorAll(`[${this.config.attr_validate_rule}]`)];
    let allFieldsByReq = [...node.querySelectorAll(`[${this.config.attr_validate_req}]`)];
    let allIgnoredFields = isIgnoreEnable ? [...node.querySelectorAll(`[${this.config.attr_validate_autoscan_ignore}]`)] : [];
    let allFields = allFieldsByRule;
    allFieldsByReq.forEach((field, index) => {
      allFields.push(field);
    })
    allFields = allFields.filter((field)=> !allIgnoredFields.includes(field));
    return allFields;
  }

  __findNoPartFields() {
    let allFields = this.__findAllFieldsInNode(this.areaNode)
    let partFieldsByRule = [...this.areaNode.querySelectorAll(`[${this.config.attr_validate_part}] [${this.config.attr_validate_rule}]`)];
    let partFieldsByReq = [...this.areaNode.querySelectorAll(`[${this.config.attr_validate_part}] [${this.config.attr_validate_req}]`)];
    let allPartsFields = partFieldsByRule;
    partFieldsByReq.forEach((field, name) => {
      allPartsFields[name] = field;
    })
    return allFields.filter(field => !allPartsFields.includes(field));
  }

  __validateNoPartFields() {
    return this.__validateFields(this.parts[this.config.noPartFieldsPartName].fields)
  }

  __validateFields(fields) {
    let results = [];
    for (const fieldId in fields) {
      let fieldData = fields[fieldId];
      let fieldNode = fieldData.node;
      results[fieldId] = this.__validateField(fieldData);
      results[fieldId].node = fieldNode;
    }
    return results;
  }

  __validateField(fieldData) {
    console.log(fieldData);
    let value = fieldData.node.value;
    //поле не обязательное и пустое
    if (!fieldData.isRequired && !value) {
      return this.validatorMethods.__makeSuccess();
    }
    //поле обязательное но не заполненно
    if (fieldData.isRequired && value.length === 0) {
      return this.validatorMethods.__makeError(this.config.validate_error_req);
    }
    //бязательно, без правил валидации и заполненно
    if (fieldData.isRequired && value.length !== 0 && fieldData.validateRules.length === 0) {
      return this.validatorMethods.__makeSuccess();
    }
    let result;
    let depsValues = this.__collectDepValues(fieldData);

    for (const validateRule of fieldData.validateRules) {
      result = this.validatorMethods[validateRule](value, ...depsValues);
      if (!result.isSuccess) {
        return result;
      }
    }
    return result;
  }

  __toggleFieldReq(fieldNode, partName = '') {
    let fieldId = fieldNode.id;
    let currentFieldList = this.__getFieldListById(partName, partName); //вот это надо переделать так как теперь нет отдельного списка для полей без парта
    if (!currentFieldList[fieldId]) {
      console.log(`add field #${fieldId} before change req`);
      return false;
    }
    if (fieldNode.getAttribute(this.config.attr_validate_req) === null) {
      fieldNode.setAttribute(this.config.attr_validate_req, 'true');
    } else {
      fieldNode.removeAttribute(this.config.attr_validate_req);
    }
    currentFieldList[fieldId] = this.__collectFieldData(fieldNode);
    return true;
  }

  __collectDepValues(fieldData) {
    let fieldNode = fieldData.node;
    let depsString = fieldNode.getAttribute('validate_dep');
    if (!depsString) {
      return [];
    }
    let depsIds = depsString.split(',').map(depId=>depId.trim());
    return depsIds.map(id => document.getElementById(id).value);
  }
}

class ValidatorMethods {

  checkCardLimit(cardLimit) {
    return this.__makeError('Ошибка, не установлено правило валидации')
  }

  checkGoodName(goodName) {
    if (goodName.length > 2) {
      return this.__makeSuccess();
    }
    return this.__makeError('Слишком короткое название продукта')
  }

  checkGoodModel(goodModel) {
    if (goodModel.length > 0 && goodModel.length < 91) {
      return this.__makeSuccess();
    }
    return this.__makeError('Слишком длинная модель продукта')
  }

  checkGoodBrand(goodBrand) {
    if (goodBrand.length > 0 && goodBrand.length < 91) {
      return this.__makeSuccess();
    }
    return this.__makeError('Слишком длинный бренд продукта')
  }

  checkGoodPrice(price) {
    let numberedPrice = Number(price.replace(' ', ''));
    if (numberedPrice > 1) {
      return this.__makeSuccess();
    }
    return this.__makeError('Неверная цена');
  }

  checkFullName(fullName) {
    if (
      !fullName.length > 8
      || !fullName.match(/^[А-я\- ]+$/)
    ) {
      return this.__makeError('Допускается только кириллица')
    }

    return this.__makeSuccess();
  }

  checkFirstName(firstName) {
    if (
      !this.__checkIsString(firstName)
      || firstName.length < 2
    ) {
      return this.__makeError('Имя должно быть длинее двух символов');
    }

    if (firstName.match(/(вич|вна)$/m)) {
      return this.__makeError('Имя не может заканчиваться на \'вич\' или \'вна\'');
    }

    if (!firstName.match(/[А-я]+([ -]?[А-я]+){0,3}/)) {
      return this.__makeError('Имя должно состоять из кириллицы');
    }

    return this.__makeSuccess();
  }

  checkMiddleName(middleName) {
    if (
      !this.__checkIsString(middleName)
      || middleName.length < 2
    ) {
      return this.__makeError('Отчество должно быть длинее двух символов');
    }

    if (!middleName.match(/[А-я]+([ -]?[А-я]+){0,3}/)) {
      return this.__makeError('Отчество должно состоять из кириллицы');
    }

    return this.__makeSuccess();
  }

  groupIncomeType(a,b,c) {
    console.log(a,b,c);
  }

  checkLastName(lastName) {
    let result = this.checkMiddleName(lastName);
    if (!result.isSuccess) {
      return this.__makeError(this.checkMiddleName(lastName).errorMessage.replace('Отчество', 'Фамилия'));
    }
    return this.__makeSuccess();
  }

  checkBirthDate(birthDateStr) {
    // let birthDate = new Date(birthDateStr);
    let birthDate = this.__strToDate(birthDateStr)
    if (!birthDate) {
      return this.__makeError('Неверно введен формат даты');
    }

    let now = new Date();
    if (now < birthDate || now.toDateString() === birthDate.toDateString()) {
      return this.__makeError('Введена дата из будущего');
    }
    if (this.__getFullYearsOld(birthDate) < 18) {
      return this.__makeError('Возраст должен быть более 18 лет');
    }
    if (this.__getFullYearsOld(birthDate) > 75) {
      return this.__makeError('Возраст должен быть менее 76 лет');
    }

    return this.__makeSuccess();
  }

  checkPassportSerNum(passportSerNum) {
    let error = this.__makeError('Неверный номер паспорта');
    if (passportSerNum.length !== 11) {
      return error;
    }

    let serNum = passportSerNum.split(' ');
    if (serNum.length !== 2) {
      return error;
    }

    let series = serNum[0];
    let number = serNum[1];

    let serValidateResult = this.checkPassportSer(series);
    let numberValidateResult = this.checkPassportNum(number);
    if (!serValidateResult.isSuccess || !numberValidateResult.isSuccess) {
      return error;
    }

    return this.__makeSuccess();
  }

  checkPassportNum(passportNum) {
    if (!passportNum.match(/^\d{6}$/) || passportNum === '000000') {
      return this.__makeError('Неверный номер пасспорта')
    }
    return this.__makeSuccess();
  }

  checkPassportSer(passportSer) {
    if (!passportSer.match(/^\d{4}$/) || passportSer === '0000') {
      return this.__makeError('Неверный номер паспорта')
    }
    return this.__makeSuccess();
  }

  checkPassportIssueDate(passportIssueDateStr, birthDateStr) {
    let birthDateValidationResult = this.checkBirthDate(birthDateStr);
    if (!birthDateValidationResult.isSuccess) {
      return this.__makeError('Некорректная дата рождения')
    }

    let passportIssueDate = this.__strToDate(passportIssueDateStr)
    let birthDate = this.__strToDate(birthDateStr)
    if (!passportIssueDate) {
      return this.__makeError('Неверно введен формат даты');
    }

    let passportChange20Date = new Date(birthDate);
    passportChange20Date.setFullYear(birthDate.getFullYear() + 20)
    let passportChange45Date = new Date(birthDate);
    passportChange45Date.setFullYear(birthDate.getFullYear() + 45)
    let passportGain14Date = new Date(birthDate);
    passportGain14Date.setFullYear(birthDate.getFullYear() + 14)

    let now = new Date();
    if (passportIssueDate > now) {
      return this.__makeError('Дата выдачи паспорта не может быть будущей')
    }

    let age = this.__getFullYearsOld(birthDate);
    if (
      age >= 45 && passportIssueDate < passportChange45Date
      || age >= 20 && passportIssueDate < passportChange20Date
      || age >= 14 && passportIssueDate < passportGain14Date
    ) {
      return this.__makeError('Паспорт просрочен');
    }
    return this.__makeSuccess();
  }

  checkPassportIssuer(issuer) {
    if (!issuer.match(/^[А-я \-.\/,0-9№]+$/) || issuer.length < 4) {
      return this.__makeError('Неверно введено отделение')
    }
    return this.__makeSuccess();
  }

  checkPassportDivisionCode(passportDivCode) {
    if (passportDivCode.match(/\d{3}[- ]?\d{3}/)) {
      return this.__makeSuccess();
    }
    return this.__makeError('Неверный код подразделения')
  }

  checkAddress(address) {
    if (
      !address.match(/^[А-я \-.\/,0-9№]+$/) || address.length < 4
      || address.match(/^\d/)
    ) {
      return this.__makeError('Неверно введен адрес')
    }
    return this.__makeSuccess();
  }

  checkRegistrateDate(dateStr) {
    let date = this.__strToDate(dateStr)
    if (!date) {
      return this.__makeError('Неверно введен формат даты');
    }

    let now = new Date();
    if (now <= date) {
      return this.__makeError('Введена дата из будущего');
    }

    return this.__makeSuccess();
  }

  checkWorkName(workName) {
    if (
      workName.match(/^[^\d]+/)
    ) {
      return this.__makeSuccess();
    }
    return this.__makeError('Ошибка в названии организации')
  }

  checkAverageIncome(income) {
    if (!income.match(/^\d+$/)) {
      return this.__makeError('Неверное значение')
    }
    if (Number(income) < 10000) {
      return this.__makeError('Средний доход должен быть не менее 10000')
    }
    return this.__makeSuccess();
  }

  checkMobilePhone(mobilePhone) {
    // if (!mobilePhone.match(/^((\+7)|(7)|(8))?(( )|(\()|( \()?)(9\d{2})(\)?)([ -]?)(\d{3})([ -]?)(\d{2})([ -]?)(\d{2})$/)) {
    if (
      !mobilePhone.match(/^\(9\d{2}\) \d{3}-\d{4}$/)
      && !mobilePhone.match(/^79\d{9}$/)
    ) {
      return this.__makeError('Неверный номер телефона')
    }
    return this.__makeSuccess();
  }

  checkIndex(index) {
    if (!index.match(/^\d{6}$/)) {
      return this.__makeError('Неверное значение')
    }
    return this.__makeSuccess();
  }

  checkEmail(email) {
    if (email.match(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,10}$/i)) {
      return this.__makeSuccess();
    }
    return this.__makeError('Неверный адрес')
  }

  checkStacionarPhone(phoneNumber) {
    if (
      !phoneNumber.match(/\(\d{3}\) \d{3}-\d{4}/)
    ) {
      return this.__makeError('Неверный номер телефона')
    }

    return this.__makeSuccess()
  }

  checkMounthPeriod(period) {
    if (
      period.match(/^\d+$/)
      && Number(period) < 999
    ) {
      return this.__makeSuccess()
    }
    this.__makeError('Неверное кол-во месяцев')
  }

  checkSecretWord(secret) {
    if (secret.length < 4) {
      return this.__makeError('Секретное слово должно быть длинее 3 символов')
    }
    return this.__makeSuccess();
  }

  __getFullYearsOld(birthDate) {
    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let thisYearBirthDay = (new Date(birthDate)).setFullYear(today.getFullYear());
    if (thisYearBirthDay > today) {
      age--;
    }
    return age;
  }
  __checkIsString(value) {
    return (typeof value === 'string');
  }
  __strToDate(str) {
    //31.12.2022
    let separators = ['.', '-', '_', '/', ' '];
    for (const separatorsKey in separators) {
      let parts = str.split(separators[separatorsKey]);
      let date = new Date(parts[2], parts[1] - 1, parts[0]);
      if (date != 'Invalid Date') {
        return date;
      }
    }
    return false;
  }
  __makeError(message) {
    return new FieldValidationResult(FieldValidationResult.VALIDATION_ERROR, message);
  }
  __makeSuccess() {
    return new FieldValidationResult(FieldValidationResult.VALIDATION_SUCCESS)
  }
}

class FieldValidationResult {
  static VALIDATION_SUCCESS = true;
  static VALIDATION_ERROR = false;

  constructor(result, errorMessage = undefined) {
    this.isSuccess = result;
    this.errorMessage = errorMessage;
  }
}

let firstFormValidator = new Validator('firstForm');
console.log('final', firstFormValidator);
let secondFormValidator = new Validator('second_form');

initAddRemoveBtns();
initSubmitBtns();
initValidationErrorCleanEvents();

function initAddRemoveBtns() {
  function addInput(node, id, validator, partName = validator.config.noPartFieldsPartName) {
    let block = document.createElement('div');
    block.classList.add('addedField');
    block.innerHTML = `<label>Добавленный ${id}:
                        <input type="text" id="${id}" name="ccustom" validate_req>
                    </label>`
    node.appendChild(block);
    let input = block.querySelector('input');
    validator.addField(input, partName);
    console.log(validator)

  }
  function removeLastElem(node, validator, partName = '') {
    let block = node.lastChild;
    let input = block.querySelector('input');
    validator.removeField(input, partName);
    block.remove();
    console.log(validator)
  }
  function deleteAllAdded(node, validator, partName = '') {
    validator.removeAllFieldsInNode(node, partName)
    node.innerHTML = '';
    console.log(validator)
  }

  let id23 = 0;
  let addSecondFormThirdPartButton = document.getElementById('add_2_3');
  addSecondFormThirdPartButton.addEventListener('click', () => {
    addInput(document.getElementById('addedList23'), `added23_${id23++}`, secondFormValidator, '3')
  })
  let removeSecondFormThirdPartButton = document.getElementById('remove_2_3');
  removeSecondFormThirdPartButton.addEventListener('click', () => {
    if (id23 === 0) return;
    id23--;
    removeLastElem(document.getElementById('addedList23'), secondFormValidator, '3')
  })
  let removeAllSecondFormThirdPartButton = document.getElementById('remove_2_3_all');
  removeAllSecondFormThirdPartButton.addEventListener('click', () => {
    id23 = 0;
    deleteAllAdded(document.getElementById('addedList23'), secondFormValidator, '3')
  })

  let id2 = 0;
  let addSecondFormButton = document.getElementById('add_2');
  addSecondFormButton.addEventListener('click', () => {
    addInput(document.getElementById('addedList2'), `added2_${id2++}`, secondFormValidator)
  })
  let removeSecondFormButton = document.getElementById('remove_2');
  removeSecondFormButton.addEventListener('click', () => {
    if (id2 === 0) return;
    id2--;
    removeLastElem(document.getElementById('addedList2'), secondFormValidator)
  })
  let removeAllSecondFormButton = document.getElementById('remove_2_all');
  removeAllSecondFormButton.addEventListener('click', () => {
    id2 = 0;
    deleteAllAdded(document.getElementById('addedList2'), secondFormValidator)
  })
}

function initSubmitBtns() {
  let firstFormButton = document.getElementById('1');
  firstFormButton.addEventListener("click", () => {
    let result = firstFormValidator.validateAll()
    handleValidationErrors(result);
  })


  secondFormValidator.addCustomValidateMethod('ccustom', (value) => {
    if (value !== 'q') {
      return secondFormValidator.validatorMethods.__makeError('Ошибка кастомного метода');
    }
    return secondFormValidator.validatorMethods.__makeSuccess();
  })

  let step1Button = document.getElementById('2_1');
  step1Button.addEventListener("click", () => {
    let result = secondFormValidator.validatePart('1')
    handleValidationErrors(result);
  })

  let step2Button = document.getElementById('2_2');
  step2Button.addEventListener("click", () => {
    let result = secondFormValidator.validatePart('2')
    handleValidationErrors(result);
  })

  let step3Button = document.getElementById('2_3');
  step3Button.addEventListener("click", () => {
    let result = secondFormValidator.validatePart('3')
    handleValidationErrors(result);
  })

  let form2Button = document.getElementById('2');
  form2Button.addEventListener("click", () => {
    let result = secondFormValidator.validateAll()
    handleValidationErrors(result);
  })

  function handleValidationErrors(errors) {
    console.log(errors);
    for (const fieldId in errors) {
      if (!errors[fieldId].isSuccess) {
        errors[fieldId].node.style.backgroundColor = '#ffeaea';
      } else {
        errors[fieldId].node.style.backgroundColor = '#f5ffea';
      }
    }
  }
}

function initValidationErrorCleanEvents() {
  let inputs = document.querySelectorAll('[validate_rule]')
  let inputs2 = document.querySelectorAll('[validate_req]')
  for (const input of inputs) {
    input.addEventListener('input', (e)=>{
      handleValidationErrorClean(e);
    })
  }
  for (const input of inputs2) {
    input.addEventListener('input', (e)=>{
      handleValidationErrorClean(e);
    })
  }
  function handleValidationErrorClean(e) {
    e.target.style.backgroundColor = '#ffffff';
  }
}
