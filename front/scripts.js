{
  function getGet(key) {
    let p = window.location.search;
    p = p.match(new RegExp(key + '=([^&=]+)'));
    return p ? p[1] : false;
  }

  let currLogin = '';
  let currentRole = '';
  let currLoginLayout = document.getElementById('cur_login');
  let currentRoleLayout = document.getElementById('cur_role');
  function setCurrentUser(login, role) {
    currLogin = login;
    switch (role) {
      case 0:
        currentRole = 'Нет роли';
        break;
      case 1:
        currentRole = 'admin';
        break;
      case 2:
        currentRole = 'moderator';
        break;
      case 3:
        currentRole = 'user';
        break;
    }
    currLoginLayout.innerHTML = currLogin;
    currentRoleLayout.innerHTML = currentRole;
  }

  function initExitButton() {
    let exitButton = document.getElementById('exit_button');
    exitButton.addEventListener('click', (e) => {
      fetch('/api/auth/logout', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'GET',
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code});
          setCurrentUser('Не авторизован', 0);
        })
    })
  }

  function initGetCurrentUserButton() {
    let exitButton = document.getElementById('get_current_user_button');
    exitButton.addEventListener('click', (e) => {
      fetch('/api/auth/get_current_user', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'GET',
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code + ' ' + result.message});
        })
    })
  }

  function initSpoilers() {
    document.addEventListener('DOMContentLoaded', function() {
      let elems = document.querySelectorAll('.collapsible.expandable');
      let instances = M.Collapsible.init(elems, {
        accordion: false,
        inDuration: 100
      });
    });
  }

  function initAuthForm() {
    let form = document.getElementById('auth_form');
    form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let formData = new FormData();
      console.log(form.login.value);
      let data = JSON.stringify({
        login: form.login.value,
        password: form.password.value,
      })
      formData.append('userData', data);

      fetch('/api/auth/login', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'POST',
        body: formData
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code});
          setCurrentUser(result.data.login, result.data.role);
        })
    })
  }

  function initRegForm() {
    let form = document.getElementById('registration_form');
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      // let login = form.login;
      // let password = form.password;
      // let name = form.login;
      // let middleName = form.middlename;
      // let lastName = form.lastname;

      let formData = new FormData();
      console.log(form.login.value);
      let data = JSON.stringify({
        login: form.login.value,
        password: form.password.value,
        name: form.name.value,
        middleName: form.middlename.value,
        lastName: form.lastname.value
      })
      formData.append('userData', data);

      fetch('/api/auth/register', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'POST',
        body: formData
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code})
        })
    })
  }

  function initChangeEmailForm() {
    let form = document.getElementById('change_email_form');
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      // let email = form.email;

      let formData = new FormData();
      formData.append('email', form.email.value);

      fetch('/api/auth/change_email', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'POST',
        body: formData
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code})
        })
    })
  }

  function initForgotPasswordForm() {
    let form = document.getElementById('forgot_password_form');
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      // let email = form.email;

      let formData = new FormData();
      formData.append('email', form.email.value);

      fetch('/api/auth/send_reset_password', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'POST',
        body: formData
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code})
        })
    })
  }

  function initResetPasswordForm() {
    let form = document.getElementById('reset_password_form');
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      let password = form.password.value;
      let token = getGet('token');
      let formData = new FormData();
      formData.append('password', password);
      formData.append('token', token);

      fetch('/api/auth/reset_password', {
        mode: "no-cors",
        headers: {
          'Accept': 'application/json; charset=UTF-8',
        },
        method: 'POST',
        body: formData
      }).then(response => response.json())
        .then(result => {
          console.log(result)
          M.toast({html: result.code})
        })
    })
  }

  function init() {
    initGetCurrentUserButton();
    initExitButton();
    initSpoilers();
    initRegForm();
    initAuthForm();
    initForgotPasswordForm();
    initChangeEmailForm();
    initResetPasswordForm();
  }

  init();
}
