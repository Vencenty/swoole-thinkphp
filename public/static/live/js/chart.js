$(function () {
  const url = 'ws://swoole.vencenty.cn:8888'

  if(!window.WebSocket) {
    alert('换个浏览器吧，您的浏览器不支持websocket');
  }

  const ws = new WebSocket(url);
  ws.onopen = function () {
    console.log('连接成功');
    $('#preload').css({display: 'none'})
  }

  ws.onmessage = function (evt) {
    const data = JSON.parse(evt.data);

    const dom = `
      <div class="comment">
        <span>${data.user}</span>
        <span>${data.content}</span>
      </div>
    `;

    $('#comments').prepend(dom)
  };

  ws.onclose = function () {

  }
})