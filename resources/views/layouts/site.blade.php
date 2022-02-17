<!DOCTYPE html>
<html>
    <head>
        <title>Title Here</title>
       <link rel="stylesheet" type="text/css" href="{{ asset('css/build.css') }}" />
        <script type="text/javascript" src="{{ asset('js/build.js') }}"></script>
       <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    <body>
        <header class="header">
            <a class="header__logo" href="/">
              <img class="header__logo__img" src="{{ asset('images/Group_131.png') }}" alt="Kimberley Caravans Logo">
            </a>
            @include('components.nav-bar')
        </header>
        @yield('body')
        <footer class="site-footer">
            <div class="site-footer__about">
                <div class="site-footer__about__inner">
                    <div class="site-footer__about__left">
                        <div class="site-footer__about__title">
                            Find your nearest <br /> Kimberley branch
                        </div>
                        <div class="site-footer__about__social">
                            <span class="site-footer__about__social__title">Social</span>
                            <ul class="site-footer__about__social__list">
                            <li class="site-footer__about__social__list__item">
                                    <a href="{{ config('kimberley.social.fb') }}" target='_blank'><i class="fa-brands fa-facebook-square"></i></a>
                                </li>
                                <li class="site-footer__about__social__list__item">
                                    <a href="{{ config('kimberley.social.in') }}" target='_blank'><i class="fa-brands fa-instagram"></i></a>
                                </li>
                                <li class="site-footer__about__social__list__item">
                                    <a href="{{ config('kimberley.social.tw') }}" target='_blank'><i class="fa-brands fa-twitter-square"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="site-footer__about__right">
                    <div class="site-footer__about__locations">
                    <div class="site-footer__about__locations__item">
                            <div class="site-footer__about__locations__item__title">
                                    Kimberley Nottingham
                                </div>
                                <address class="site-footer__about__locations__item__address">
                                    {{ config('kimberley.address.nottingham') }} Find us
                                </address>
                                <a class="site-footer__about__locations__item__link" href="tel:01159382401">
                                    Sales: {{ config('kimberley.phone.sales') }}
                                </a>
                            </div>    
                            <div class="site-footer__about__locations__item">
                            <div class="site-footer__about__locations__item__title">
                                    Kimberley Darlingtom
                                </div>
                                <address class="site-footer__about__locations__item__address">
                                    {{ config('kimberley.address.darlington') }} Find us
                                </address>
                                <a class="site-footer__about__locations__item__link" href="tel:01159382401">
                                    Sales: {{ config('kimberley.phone.sales') }}
                                </a>
                            </div>    
                            <div class="site-footer__about__locations__item">
                            <div class="site-footer__about__locations__item__title">
                                    Kimberley Chesterfield
                                </div>
                                <address class="site-footer__about__locations__item__address">
                                    {{ config('kimberley.address.chesterfield') }} Find us
                                </address>
                                <a class="site-footer__about__locations__item__link" href="tel:01159382401">
                                    Sales: {{ config('kimberley.phone.sales') }}
                                </a>
                            </div>      
                        </div>      
                    </div>
                </div>
            </div>
            <div class="site-footer__menus">
                <div class="site-footer__menus__inner">
                    <div class="site-footer__menus__row">
                        <div class="site-footer__menus__column site-footer__menus__column--logo">
                            <div class="site-footer__logo">
                                <img src="{{ asset('images/footer/footer-logo.png') }}" alt="Kimberley Caravans Logo" />
                            </div>
                        </div>
                        <div class="site-footer__menus__column site-footer__menus__column--list">
                        <ul class="site-footer__menus__menu">
                                <li class="site-footer__menus__item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('page.about') }}">About Us</a></li>
                                <li class="site-footer__menus__item"><a href="">Services</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('locations.index') }}">Locations</a></li>
                            </ul>
                        <ul class="site-footer__menus__menu">
                                <li class="site-footer__menus__item"><a href="{{ route('news.index') }}">News and Information</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('caravans.new') }}">New Caravans</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('caravans.used') }}">Used Caravans</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('motorhomes.new') }}">New Motor Homes</a></li>
                            </ul>
                            <ul class="site-footer__menus__menu">
                                <li class="site-footer__menus__item"><a href="{{ route('motorhomes.used') }}">Used Motor Homes</a></li>
                                <li class="site-footer__menus__item"><a href="{{ route('awnings.index') }}">Awnings</a></li>
                            </ul>
                        </div>
                        <div class="site-footer__menus__column site-footer__menus__column--button">
                            <a href="#" class="site-footer__menus__action">
                                Sign up to our mailer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer__copyright">
                <div class="site-footer__copyright__inner">
                    <div class="site-footer__copyright__text">
                        Kimberley Caravan Centre Ltd. Registered office: Eastwood Road, Kimberley, Nottingham NG16 2HX. Registered in England & Wales No. 01702419
                    </div>
                    <ul class="site-footer__copyright__menu">
                        <li class="site-footer__copyright__menu__item"><a href="{{ route('page.privacy') }}">Privacy Policy</a></li>
                        <li class="site-footer__copyright__menu__item"><a href="{{ route('page.tc') }}">T&Cs</a></li>
                        <li class="site-footer__copyright__menu__item"><a href="{{ route('page.cookie') }}">Cookie policy</a></li>
                        <li class="site-footer__copyright__menu__item"><a href="{{ route('page.sitemap') }}">Sitemap</a></li>
                     </ul>
                </div>
            </div>
        </footer>
        </body>
    </html>    
