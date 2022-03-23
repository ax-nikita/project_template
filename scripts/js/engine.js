(() => {
  let
    logout_api = new apiRequest('logout');

  function logout() {
    logout_api.execute({}, () => {
      document.location.href = "/";
    });
  }

  new axModularFunction('logout', (node) => {
    let
      button = node.axQS('button');
    button.addEventListener('click', logout);
  });
})();

(() => {
  new axModularFunction('instruction', (node) => {
    node.axQSA('li').forEach(el => {
      el.addEventListener('click', () => {
        el.classList.add('active');
      }, true);
    })
  });
})();


