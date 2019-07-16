

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Realtime Form Validation</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/formhack.css')}}">
    <script src="{{asset('js/jquery-3.3.0.js')}}"></script>

    <style>
        .err{
            color: red !important;
            border: 1px solid red !important;
        }
    </style>
</head>
<body>

<div class="container">
    @if($errors->all())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(@Session::has('success'))
            <script>alert('Success! Register Complete')</script>
    @endif
    <form class="registration" id="registration" method="post" action="{{ route('registerCustomer') }}">
        @csrf
        <h1>Registration Form</h1>
        <label for="username">
            <span>Username</span>
            <input type="text" id="username" name="username" minlength="3" autocomplete="off" required>
            <ul class="input-requirements">
                <li>Bạn không được để trống trường này</li>
                <li>Bạn không thể nhập giá trị đặc biệt</li>
                <li>Độ dài tối đa là 255 ký tự</li>
            </ul>
        </label>

        <label for="email">
            <span>Email</span>
            <input type="text" id="email" name="email" onblur="checkEmail()" minlength="3" autocomplete="off" required>
            <ul class="input-requirements"  id="emailErr">
            </ul>
            <ul class="input-requirements" >
                <li>Bạn không được để trống trường này</li>
                <li>Định dạng Email không đúng </li>
            </ul>

        </label>

        <label for="password">
            <span>Password</span>
            <input type="password" id="password" name="password" maxlength="100" minlength="8" required>
            <ul class="input-requirements">
                <li>Bạn không được để trống trường này</li>
                <li>Mật khẩu tối thiểu là 8 ký tự</li>
                <li>Mật khẩu phải chứa ít nhất một số</li>
                <li>Mật khẩu phải chứa ít nhất một chữ thường</li>
                <li>Mât khẩu phải chứa ít nhật một chữ hoa</li>
                <li>Mật khẩu phải chưa giá trị đặc biệt (e.g. @ !)</li>
            </ul>
        </label>

        <label for="password_repeat">
            <span>Repeat Password</span>
            <input type="password" id="password_repeat" name="password_confirmation" maxlength="100" minlength="8" required>
        </label>
        <br>
        <input type="submit" >
    </form>
</div>

{{--script kiểm tra các giá trị nhập vào có phù hợp với điều kiện đề ra hay không và chăc chăn nó là real time hehe--}}
<script type="text/javascript">
 //khởi tạo
    function CustomValidation(input) { // tạo một fuction
        this.invalidities = []; //tạo một mảng để theo dõi thông báo
        this.validityChecks = []; //tạo một mảng để theo dõi những giá trị hợp lệ, thực hiện kiểm tra điều kiện và trả về cho người dùng
        this.inputNode = input; // thêm giá trị đầu vào
        this.registerListener(); // chưa hiểu cái này như nào, chỉ biết nó để dùng kích hoạt sự kiện submit register
    }

    CustomValidation.prototype = {
        addInvalidity: function(message) {
            this.invalidities.push(message);
        },
        getInvalidities: function() {
            return this.invalidities.join('. \n');
        },
        checkValidity: function(input) {
            for ( var i = 0; i < this.validityChecks.length; i++ ) {
                var isInvalid = this.validityChecks[i].isInvalid(input);
                if (isInvalid) {
                    this.addInvalidity(this.validityChecks[i].invalidityMessage);
                }
                var requirementElement = this.validityChecks[i].element;
                if (requirementElement) {
                    if (isInvalid) {
                        requirementElement.classList.add('invalid');
                        requirementElement.classList.remove('valid');
                    } else {
                        requirementElement.classList.remove('invalid');
                        requirementElement.classList.add('valid');
                    }

                } // end if requirementElement
            } // end for
        },
        checkInput: function() { // checkInput now encapsulated

            this.inputNode.CustomValidation.invalidities = [];
            this.checkValidity(this.inputNode);

            if ( this.inputNode.CustomValidation.invalidities.length === 0 && this.inputNode.value !== '' ) {
                this.inputNode.setCustomValidity('');
            } else {
                var message = this.inputNode.CustomValidation.getInvalidities();
                this.inputNode.setCustomValidity(message);
            }
        },
        registerListener: function() { //register the listener here

            var CustomValidation = this;

            this.inputNode.addEventListener('keyup', function() {
                CustomValidation.checkInput();
            });


        }

    };



    //thực hiện

    var usernameValidityChecks = [
        {
            isInvalid: function(input) {
                var countValue = input.value.length = 0;
                return countValue ? true : false;
            },
            invalidityMessage: 'Bạn không được để trống trường này',
            element: document.querySelector('label[for="username"] .input-requirements li:nth-child(1)')
        },
        {
            isInvalid: function(input) {
                var illegalCharacters = input.value.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g);
                return illegalCharacters ? true : false;
            },
            invalidityMessage: 'Bạn không thể nhập giá trị đặc biệt',
            element: document.querySelector('label[for="username"] .input-requirements li:nth-child(2)')
        },
        {
            isInvalid: function (input) {
                var maxvalue = input.value.length > 255;
                return maxvalue ? true : false;
            },
            invalidityMessage: 'Username của bạn quá dài',
            element:document.querySelector('label[for="username"] .input-requirements li:nth-child(3)')
        }
    ];

    var emailValidityChecks = [
        {
            isInvalid: function (input) {
                return input.value.length = 0;
            },
            invalidityMessage: 'Bạn không được để trống trường này',
            element: document.querySelector('label[for="email"] .input-requirements li:nth-child(1)')
        },
        {
            isInvalid: function (input) {
                var emailvalue = input.value.match(/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i);
                return emailvalue ? false : true;
            },
            invalidityMessage: 'Định dạng Email không đúng',
            element: document.querySelector('label[for="email"] .input-requirements li:nth-child(2)')
        }
    ]

    var passwordValidityChecks = [
        {
            isInvalid: function(input) {
                return input.value.length = 0;
            },
            invalidityMessage: 'Bạn không được để truống trường này',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(1)')
        },
        {
            isInvalid: function (input) {
                var minvalue = input.value.length < 8;
                return minvalue ? true : false;
            },
            invalidityMessage: 'Password phải chứa ít nhất 8 ký tự',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(2)')
        },
        {
            isInvalid: function(input) {
                return !input.value.match(/[0-9]/g);
            },
            invalidityMessage: 'Mật khẩu phải chứa ít nhất một số ',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(3)')
        },
        {
            isInvalid: function(input) {
                return !input.value.match(/[a-z]/g);
            },
            invalidityMessage: 'Mật khẩu phải chứa ít nhất một chữ thường',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(4)')
        },
        {
            isInvalid: function(input) {
                return !input.value.match(/[A-Z]/g);
            },
            invalidityMessage: 'Mật khẩu phải chứa it nhất một chứ HOA',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(5)')
        },
        {
            isInvalid: function(input) {
                return !input.value.match(/[\!\@\#\$\%\^\&\*]/g);
            },
            invalidityMessage: 'Mật khẩu phải chưa giá trị đặc biệt (e.g. @ !)',
            element: document.querySelector('label[for="password"] .input-requirements li:nth-child(6)')
        }
    ];

    var passwordRepeatValidityChecks = [
        {
            isInvalid: function() {
                return passwordRepeatInput.value != passwordInput.value;
            },
            invalidityMessage: 'Password confirm không giống nhau '
        }
    ];


    /* ----------------------------

        Setup CustomValidation

        Setup the CustomValidation prototype for each input
        Also sets which array of validity checks to use for that input

    ---------------------------- */

    var usernameInput = document.getElementById('username');
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var passwordRepeatInput = document.getElementById('password_repeat');

    usernameInput.CustomValidation = new CustomValidation(usernameInput);
    usernameInput.CustomValidation.validityChecks = usernameValidityChecks;

    emailInput.CustomValidation = new CustomValidation(emailInput);
    emailInput.CustomValidation.validityChecks = emailValidityChecks;

    passwordInput.CustomValidation = new CustomValidation(passwordInput);
    passwordInput.CustomValidation.validityChecks = passwordValidityChecks;

    passwordRepeatInput.CustomValidation = new CustomValidation(passwordRepeatInput);
    passwordRepeatInput.CustomValidation.validityChecks = passwordRepeatValidityChecks;





    var inputs = document.querySelectorAll('input:not([type="submit"])');
    var submit = document.querySelector('input[type="submit"');
    // var form = document.getElementById('registration');
    //
    function validate() {
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].CustomValidation.checkInput();
        }
        return true;
    }
    //
    submit.addEventListener('click', function(){
     if(validate()){
         $('#registration').submit();
     }
    });
    // form.addEventListener('submit', validate);
</script>

{{--script sử dụng ajax để check xem những giá trị đó trong có trùng với data trong db hay không, ồ dĩ nhiên là nó real time rồi, không thì viết làm éo gì  hehe--}}
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    })

    function checkEmail(){
        var email = document.getElementById('email').value;
        $.ajax({
            type: "POST",
            url: '{{url('checkemail')}}',
            data: {email:email},
            dataType: "json",
            success: function(res) {
                if(res.exists){
                    $("#email").addClass('err');
                    $('#emailErr').append('<li style="color: red; list-style-type: none">Email đã được sử dụng. Vui lòng thay email khác</li>')
                }
            },
            error: function (jqXHR, exception) {

            }
        });
        $('#email').change(function () {
            $(this).removeClass('err');
            $('#emailErr').empty();
        })
    }
</script>


</body>
</html>