(() => {
  let
    testApi = new apiRequest('Test');

  testApi.execute('', (result) => {
    try {
      result = JSON.parse(result);
      if (!result.error) {
        axQS('body').append((new axNode('div')).axVal(result.result));
      } else {
        axQS('body').append('Ошибка проверки Test_api!');
      }
    } catch (error) {
      axQS('body').append('Ошибка проверки Test_api:', new axNode('br'));
      axQS('body').append((new axNode('pre')).axVal(result));
    }
  })
})();