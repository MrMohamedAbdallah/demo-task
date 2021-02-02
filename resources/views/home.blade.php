<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        html,
        body {
            height: 100%;
        }

        .fav-btn {
            display: flex;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        .favme {
            display: block;
            font-size: .2s;
            width: auto;
            height: auto;
            cursor: pointer;
            box-shadow: none;
            transition: all 0.2s ease;
            color: #cbcdce;
            margin: 0;
        }

        .favme.active {
            color: #dc3232;
        }

        .favme:hover {
            animation: favme-hover 0.3s infinite alternate;
        }

        .favme.is_animating {
            animation: favme-anime 0.3s;
        }

        @keyframes favme-anime {
            0% {
                opacity: 1;
                /* font-size: 1rem; */
                transform: scale(1.1);
                -webkit-text-stroke-color: transparent;
            }

            25% {
                opacity: 0.6;
                color: #fff;
                /* font-size: 2rem; */
                transform: scale(1.2);
                -webkit-text-stroke-width: 1px;
                -webkit-text-stroke-color: #dc3232;
            }

            75% {
                opacity: 0.6;
                color: #fff;
                /* font-size: 3rem; */
                transform: scale(1.3);
                -webkit-text-stroke-width: 1px;
                -webkit-text-stroke-color: #dc3232;
            }

            100% {
                opacity: 1;
                /* font-size: 2rem; */
                transform: scale(1.2);
                -webkit-text-stroke-color: transparent;
            }
        }

        @keyframes favme-hover {
            0% {
                /* font-size: 3rem; */
                transform: scale(1.3);
            }

            80% {
                /* font-size: 2rem; */
                transform: scale(1.2);
            }
        }
    </style>
    <link href="//s.w.org/wp-includes/css/dashicons.css?20150710" rel="stylesheet" type="text/css">
    <title>{{ config('app.name') }}</title>
</head>

<body>


    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if($favs->count())
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Created Date</th>
                                <th>Session ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($favs as $fav)
                            <tr>
                                <td>{{ $fav->created_at }}</td>
                                <td>{{ $fav->session_id }}</td>
                                <td>
                                    <div class="fav-btn">
                                        @if($fav->fav_status)
                                            <span
                                                data-fav="{{ $fav->id }}"
                                                class="favme dashicons dashicons-heart active">
                                            </span>
                                        @else
                                            <span
                                                data-fav="{{ $fav->id }}"
                                                class="favme dashicons dashicons-heart">
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h3 class="text-center">There's no records to show yet :(</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous">
    </script>

    <script>
        // Favorite Button - Heart
        // $('.favme').click(function() {
        //     $(this).toggleClass('active');
        // });

        /* when a user clicks, toggle the 'is-animating' class */
        $(".favme").on('click touchstart', function(){
            $(this).toggleClass('is_animating');
        });

        /*when the animation is over, remove the class*/
        $(".favme").on('animationend', function(){
            $(this).toggleClass('is_animating');
        });


        const userID = {{ Auth::user()->id }};
        $('[data-fav]').click(function(){
            if(this.dataset.hold == 'true') 
                return;

            const favID = parseInt(this.dataset.fav);
            const currentStatus = this.classList.contains('active');

            this.classList.toggle('active');

            $.ajax({
                url: "{{ route('fav.toggle') }}",
                type:"POST",
                // contentType: 'application/json',
                data: {
                    fav_id: favID,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: ()=>{
                    this.dataset.hold = "true";
                },
                success: (res)=>{
                    if(res.status){
                        this.classList.add('active');
                    } else {
                        this.classList.remove('active');
                    }
                },
                error: (err)=>{
                    console.log("Request Error");
                    console.error(err);
                    
                    if(currentStatus)
                        this.classList.add('active');
                    else 
                        this.classList.remove('active');

                    
                },
                complete: ()=>{
                    this.dataset.hold = "false";
                }

            });

        });

    </script>

</body>

</html>