if(document.querySelector('.textinput')) {
    const button = document.querySelector('.submitbutton');

    console.log('Found input', button)

    button.addEventListener('click',  (e) => {
        e.preventDefault();

        const textInput = document.querySelector('.textinput')

        var formdata = new FormData();
        formdata.append("message", textInput.value);

        const requestOptions = {
            method: 'POST',
            body: formdata,
            redirect: 'follow'
          };

      fetch(`/wp-json/messages/v1/create`, requestOptions)
      .then(response => response.text())
      .then(result =>  {
        console.log(result);
        textInput.value = '';
      } )
      .catch(error => console.log('error', error));
    })
  }
};




