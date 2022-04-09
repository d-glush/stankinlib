class Validator {
  config = {
    noPartFieldsPartName: '__default__',
    attrValidateArea: 'validate-area',
    attrValidatePart: 'validate-part',
    attrValidateRule: 'validate-rule',
    attrValidateDep: 'validate-dep',
    attrValidateReq: 'validate-req',
    attrValidateGroup: 'validate-group',
    attrValidateGroupRule: 'validate-group-rule',
    attrValidateName: 'validate-name',
    attrValidateAutoscanIgnore: 'validate-autoscan-ignore',
    validateErrorReq: 'Обязательно к заполнению',
    logEnable: false,
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
    this.areaNode = document.querySelector(`[${this.config.attrValidateArea}="${validateAreaName}"]`)
    this.parts = {};
    this.__resetNoPart();
    this.__resetParts();
  }

  addField(fieldNode, partName = this.config.noPartFieldsPartName) {
    this.parts[partName].fields[fieldNode.id] = this.__collectFieldData(fieldNode);
  }

  removeField(fieldNode, partName = '') {
    let part = this.__getPartByFieldId(fieldNode.id, partName);
    if (!part) {
      this.__logError('field not founded in validator');
    }
    let fieldId = fieldNode.id;
    delete part.fields[fieldId];
  }

  addAllFieldsInNode(node, partName = this.config.noPartFieldsPartName) {
    let addingFields = this.__findAllFieldsInNode(node, false);
    if (!addingFields.length) {
      this.__logError('no fields founded in node');
    }
    let part = this.parts[partName];
    addingFields.forEach((fieldNode) => {
      let fieldId = fieldNode.id;
      part.fields[fieldId] = this.__collectFieldData(fieldNode);
    })
  }

  removeAllFieldsInNode(node, partName = '') {
    let removingFields = this.__findAllFieldsInNode(node, false);
    if (!removingFields.length) {
      this.__logError('no fields founded in node');
      return removingFields;
    }
    let part = this.__getPartByFieldId(removingFields[0].id, partName);
    let removedFields = [];
    removingFields.forEach((fieldNode) => {
      let fieldId = fieldNode.id;
      if (part.fields[fieldId]) {
        removedFields[fieldId] = fieldNode;
        delete part.fields[fieldId];
      }
    })
    return removedFields;
  }

  setFieldReq(fieldNode, partName = '') {
    if (fieldNode.getAttribute(this.config.attrValidateReq) === null) {
      this.__toggleFieldReq(fieldNode, partName);
    }
  }

  unsetFieldReq(fieldNode, partName = '') {
    if (!(fieldNode.getAttribute(this.config.attrValidateReq) === null)) {
      this.__toggleFieldReq(fieldNode, partName);
    }
  }

  addCustomValidateMethod(methodName, callable) {
    this.validatorMethods[methodName] = callable;
  }

  validateAll() {
    let result = {fields: {}, groups: {}};
    for (const partName in this.parts) {
      let partValidationResult = this.validatePart(partName)
      for (const fieldId in partValidationResult.fields) {
        result.fields[fieldId] = partValidationResult.fields[fieldId]
      }
      for (const groupName in partValidationResult.groups) {
        result.groups[groupName] = partValidationResult.groups[groupName]
      }
    }
    return result;
  }

  validatePart(partName) {
    let result = {
      fields: {},
      groups: {},
    }
    if (!this.parts[partName]) {
      this.__logError('no part founded');
      return result;
    }
    let fieldsValidationResult = this.__validateFields(this.parts[partName].fields);
    let groupsValidationResult = this.__validateGroups(this.parts[partName].groups);
    // let groupsValidationResult = {};
    result.fields = fieldsValidationResult;
    result.groups = groupsValidationResult;
    return result;
  }

  __resetParts() {
    let validateParts = this.areaNode.querySelectorAll(`[${this.config.attrValidatePart}]`)
    for (const validatePart of validateParts) {
      let partName = validatePart.getAttribute(this.config.attrValidatePart);
      this.parts[partName] = {node: validatePart, fields: {}, groups: {}};
      this.__resetPart(partName)
    }
  }

  __resetNoPart() {
    let noPartFields = this.__findNoPartFields();
    let noPartGroupFields = this.__findNoPartGroupFields();
    this.parts[this.config.noPartFieldsPartName] = {fields: {}, groups: {}};
    this.parts[this.config.noPartFieldsPartName].groups = this.__collectGroupsData(noPartGroupFields);
    for (const noPartFieldId in noPartFields) {
      let noPartField = noPartFields[noPartFieldId];
      let id = noPartField.id;
      this.parts[this.config.noPartFieldsPartName].fields[id] = this.__collectFieldData(noPartField)
    }
  }

  __resetPart(partName = this.config.noPartFieldsPartName) {
    if (partName === this.config.noPartFieldsPartName) {
      this.__resetNoPart();
      return;
    }
    let partNode = this.parts[partName].node;
    let validateFields = this.__findAllFieldsInNode(partNode);
    let groupFields = this.__findAllGroupFieldsInNode(partNode);
    this.parts[partName].groups = this.__collectGroupsData(groupFields);
    for (const validateField of validateFields) {
      let id = validateField.id;
      this.parts[partName].fields[id] = this.__collectFieldData(validateField)
    }
  }

  __findAllFieldsInNode(node, isIgnoreEnable = true) {
    let allFieldsByRule = [...node.querySelectorAll(`[${this.config.attrValidateRule}]`)];
    let allFieldsByReq = [...node.querySelectorAll(`[${this.config.attrValidateReq}]`)];
    let allIgnoredFields = isIgnoreEnable ? [...node.querySelectorAll(`[${this.config.attrValidateAutoscanIgnore}]`)] : [];
    let allFields = allFieldsByRule;
    allFieldsByReq.forEach((field, index) => {
      allFields.push(field);
    })
    allFields = allFields.filter((field)=> !allIgnoredFields.includes(field));
    return allFields;
  }

  __findAllGroupFieldsInNode(node, isIgnoreEnable = true) {
    let allGroupFields = [...node.querySelectorAll(`[${this.config.attrValidateGroup}]`)];
    let allIgnoredGroupFields = isIgnoreEnable
      ? [...node.querySelectorAll(`[${this.config.attrValidateAutoscanIgnore}][${this.config.attrValidateGroup}]`)]
      : [];
    return allGroupFields.filter(field => !allIgnoredGroupFields.includes(field));
  }

  __findNoPartFields() {
    let allFields = this.__findAllFieldsInNode(this.areaNode)
    let partFieldsByRule = [...this.areaNode.querySelectorAll(`[${this.config.attrValidatePart}] [${this.config.attrValidateRule}]`)];
    let partFieldsByReq = [...this.areaNode.querySelectorAll(`[${this.config.attrValidatePart}] [${this.config.attrValidateReq}]`)];
    let allPartsFields = partFieldsByRule;
    partFieldsByReq.forEach((field, name) => {
      allPartsFields[name] = field;
    })
    return allFields.filter(field => !allPartsFields.includes(field));
  }

  __findNoPartGroupFields() {
    let allGroups = this.__findAllGroupFieldsInNode(this.areaNode)
    let partGroups = [...this.areaNode.querySelectorAll(`[${this.config.attrValidatePart}] [${this.config.attrValidateGroup}]`)];
    return allGroups.filter(group => !partGroups.includes(group));
  }

  __validateFields(fields) {
    let results = {};
    for (const fieldId in fields) {
      let fieldData = fields[fieldId];
      let fieldNode = fieldData.node;
      results[fieldId] = this.__validateField(fieldData);
      results[fieldId].node = fieldNode;
    }
    return results;
  }

  __validateGroups(groups) {
    let results = {};
    for (let groupName in groups) {
      let groupData = groups[groupName];
      results[groupName] = this.__validateGroup(groupData);
      results[groupName].fields = groupData.fields;
    }
    return results;
  }

  __validateField(fieldData) {
    let value = this.__fieldGetValue(fieldData.node);
    //поле не обязательное и пустое
    if (!fieldData.isRequired && !value) {
      return this.validatorMethods.__makeSuccess();
    }
    //поле обязательное и пустое
    if (fieldData.isRequired && !value) {
      return this.validatorMethods.__makeError(this.config.validateErrorReq);
    }
    //обязательно, без правил валидации и заполненно
    if (fieldData.isRequired && value && fieldData.validateRules.length === 0) {
      return this.validatorMethods.__makeSuccess();
    }

    let result;
    let deps = this.__collectDepValues(fieldData);
    for (const validateRule of fieldData.validateRules) {
      result = this.validatorMethods[validateRule](value, deps);
      if (!result.isSuccess) {
        return result;
      }
    }
    return result;
  }

  __validateGroup(groupData) {
    let fields = groupData.fields;
    let data = {allValues: []}
    for (let fieldId in fields) {
      let fieldData = fields[fieldId];
      let node = fieldData.node;
      let validateName = fieldData.validateName;
      let value = this.__fieldGetValue(node);
      data[validateName] = value;
      data.allValues.push(value)
    }
    let result = {};
    for (const validateRule of groupData.validateRules) {
      result = this.validatorMethods[validateRule](data);
      if (!result.isSuccess) {
        return result;
      }
    }
    return result;
  }

  __fieldGetValue(node) {
    if (node.type === 'checkbox' || node.type === 'radio') {
      return node.checked;
    } else {
      return node.value;
    }
  }

  __collectDepValues(fieldData) {
    let result = {allValues: []};
    let fieldNode = fieldData.node;
    let depsIds = fieldData.dependencies;
    if (!depsIds) {
      return result;
    }
    depsIds.forEach(id => {
      let value = document.getElementById(id).value;
      let validateName = fieldNode.getAttribute(this.config.attrValidateName);
      result.allValues.push(value);
      result.validateName = value;
    });
    return result;
  }

  __collectFieldData(fieldNode) {
    let id = fieldNode.id;
    let isRequired = fieldNode.getAttribute(this.config.attrValidateReq) !== null;
    let depsAttr = fieldNode.getAttribute(this.config.attrValidateDep);
    let dependencies = depsAttr ? depsAttr.split(',') : [];
    let rulesAttr = fieldNode.getAttribute(this.config.attrValidateRule);
    let validateRules = rulesAttr ? rulesAttr.split(',') : [];
    let validateName = fieldNode.getAttribute(this.config.attrValidateName);
    return new FieldData(id, fieldNode, isRequired, validateRules, dependencies, validateName);
  }

  __collectGroupsData(fieldNodes) {
    let groups = {};
    for (const fieldNode of fieldNodes) {
      let groupName = fieldNode.getAttribute(this.config.attrValidateGroup);
      let groupRulesAttr = fieldNode.getAttribute(this.config.attrValidateGroupRule);
      let groupValidateRules = groupRulesAttr ? groupRulesAttr.split(',') : [];
      let fieldId = fieldNode.id;
      let fieldData = this.__collectFieldData(fieldNode);
      if (!groups[groupName]) {
        groups[groupName] = new GroupData(groupName, {}, groupValidateRules)
      } else {
        if (!groups[groupName].validateRules) {
          groups[groupName].validateRules = groupValidateRules;
        }
      }
      groups[groupName].fields[fieldId] = fieldData;
    }
    return groups;
  }

  __getPartByFieldId(fieldId, partName = '') {
    if (partName === '') {
      for (const partName in this.parts) {
        let fieldList = this.parts[partName].fields;
        let fieldIds = Object.keys(fieldList);
        if (fieldIds.includes(fieldId)) {
          return this.parts[partName];
        }
      }
    } else {
      return this.parts[partName];
    }
    return false;
  }

  __toggleFieldReq(fieldNode, partName = '') {
    let fieldId = fieldNode.id;
    let part = this. __getPartByFieldId(fieldId, partName);
    if (!part) {
      this.__logError(`add field #${fieldId} before change req`);
      return false;
    }
    if (fieldNode.getAttribute(this.config.attrValidateReq) === null) {
      fieldNode.setAttribute(this.config.attrValidateReq, 'true');
    } else {
      fieldNode.removeAttribute(this.config.attrValidateReq);
    }
    part.fields[fieldId] = this.__collectFieldData(fieldNode);
    return true;
  }

  __logError(message) {
    if (this.config.logEnable) {
      console.log(message);
    }
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

  groupIncomeType({isSalary, isPosobie, isOther}) {
    if (isOther || isSalary || isPosobie) {
      return this.__makeSuccess();
    }
    return this.__makeError('Необходимо что-то выбрать')
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

  checkPassportIssueDate(passportIssueDateStr, {allValues: [birthDateStr]}) {
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
    return new ValidationResult(ValidationResult.VALIDATION_ERROR, message);
  }
  __makeSuccess() {
    return new ValidationResult(ValidationResult.VALIDATION_SUCCESS)
  }
}

class FieldData {
  id;
  node;
  isRequired;
  validateRules;
  dependencies;
  validateName;

  constructor(
    id,
    node,
    isRequired = false,
    validateRules = [],
    dependencies = [],
    validateName = null
  ) {
    this.id = id;
    this.node = node;
    this.isRequired = isRequired;
    this.validateRules = validateRules;
    this.dependencies = dependencies;
    this.validateName = validateName;
  }
}

class GroupData {
  name;
  fields;
  validateRules;

  constructor(name, fields, validateRules = []) {
    this.name = name;
    this.fields = fields;
    this.validateRules = validateRules;
  }
}

class ValidationResult {
  static VALIDATION_SUCCESS = true;
  static VALIDATION_ERROR = false;
  isSuccess;
  errorMessage;

  constructor(result, errorMessage = undefined) {
    this.isSuccess = result;
    this.errorMessage = errorMessage;
  }
}

let firstFormValidator = new Validator('firstForm', {logEnable: true});
let secondFormValidator = new Validator('second_form', {logEnable: true});
console.log('firstForm', firstFormValidator);
console.log('secondForm', secondFormValidator);

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
    validator.removeField(input);
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
    let errorColor = '#ffeaea';
    let successColor = '#f5ffea';
    for (const fieldId in errors.fields) {
      if (!errors.fields[fieldId].isSuccess) {
        errors.fields[fieldId].node.style.backgroundColor = errorColor;
      } else {
        errors.fields[fieldId].node.style.backgroundColor = successColor;
      }
    }
    for (const groupName in errors.groups) {
      let group = errors.groups[groupName]
      let color;
      if (!errors.groups[groupName].isSuccess) {
        color = errorColor;
      } else {
        color = successColor;
      }
      for (const fieldId in group.fields) {
        let field = group.fields[fieldId].node;
        field.style.backgroundColor = color;
      }
    }
  }
}

function initValidationErrorCleanEvents() {
  let inputs = document.querySelectorAll('[validate_rule]')
  let inputs2 = document.querySelectorAll('[validate_req]')
  let inputs3 = document.querySelectorAll('[validate_group]')
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
  for (const input of inputs3) {
    input.addEventListener('input', (e)=>{
      handleValidationErrorClean(e);
    })
  }
  function handleValidationErrorClean(e) {
    e.target.style.backgroundColor = '#ffffff';
  }
}
