<!doctype html>
<html ⚡ lang="{{$appSettings['Language']??'en'}}">
<head>
    <meta charset="utf-8">
    <title>{{isset($meta)?$meta->title:$post->article->title}}</title>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>
    <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    @isset($appSettings['GA4'])
        <!-- AMP Analytics -->
        <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    @endisset

    <link rel="canonical" href="{{$post->full_path}}">

    <!-- ## Metadata -->
    <!-- The Top Stories carousel requires schema.org markup for one of the following types: Article, NewsArticle, BlogPosting, or VideoObject. [Learn more](https://developers.google.com/structured-data/carousels/top-stories#markup_specification).  -->
    <script type="application/ld+json">
        {!! \App\Utils\StructureDataUtil::getInstance()->getArticle($post) !!}
    </script>
    <meta name="viewport" content="width=device-width">
    <style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
    </style>
    <noscript>
        <style amp-boilerplate>body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }</style>
    </noscript>
    <style amp-custom>
        :root {
            --color-primary: #005AF0;
            --color-text-light: #fff;
            --color-text-dark: #000;
            --color-bg-light: #FAFAFC;

            --space-1: .5rem; /* 8px */
            --space-2: 1rem; /* 16px */

            --box-shadow-1: 0 1px 1px 0 rgba(0, 0, 0, .14), 0 1px 1px -1px rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
        }

        .figure > figcaption {
            padding: var(--space-1) var(--space-2);
        }

        .carousel .slide > amp-img > img {
            object-fit: contain;
        }

        .heading {
            padding-bottom: var(--space-1);
        }

        .heading h1 {
            font-size: 3rem;
            line-height: 3.5rem;
            margin-bottom: var(--space-2);
        }

        .heading > #summary {
            font-weight: 500;
        }

        .heading > small {
            color: var(--color-primary);
        }

        .related {
            background-color: var(--color-bg-light);
            margin: var(--space-2);
            display: flex;
            color: var(--color-text-dark);
            padding: 0;
            box-shadow: var(--box-shadow-1);
            text-decoration: none;
        }

        .related > span {
            font-weight: 400;
            margin: var(--space-1);
        }

        .related:hover {
            background-color: var(--color-bg-light);
        }

        .cookie-disclaimer {
            padding: var(--space-1);
            background: var(--color-bg-light);
            text-align: center;
            color: var(--color-text-dark);
            border-top: 1px solid var(--color-text-dark);
        }

        main {
            line-height: 1.6em;
            padding: 10px;
        }

        main img {
            max-width: 100px;
            height: auto;
        }

        main p {
            font-size: 16px;
        }

        main h1 {
            font-size: 26px;
        }

        main h2 {
            font-size: 24px;
        }

        main h3 {
            font-size: 22px;
        }

        main h4 {
            font-size: 20px;
        }

        main h5 {
            font-size: 18px;
        }

    </style>

</head>
<body>
@isset($appSettings['GA4'])
    <amp-analytics type="googleanalytics" config="https://amp.analytics-debugger.com/ga4.json" data-credentials="include">
        <script type="application/json">
{
    "vars": {
                "GA4_MEASUREMENT_ID": "{{$appSettings['GA4']}}",
                "GA4_ENDPOINT_HOSTNAME": "www.google-analytics.com",
                "DEFAULT_PAGEVIEW_ENABLED": true,
                "GOOGLE_CONSENT_ENABLED": false,
                "WEBVITALS_TRACKING": false,
                "PERFORMANCE_TIMING_TRACKING": false
    }
}
    </script>
    </amp-analytics>
@endisset
<!-- -->
<div></div>
<main>
    <div class="heading">
        <h1>{{$post->article->title}}</h1>
        <p id="summary">
            by <a href="{{config('app.url')}}">{{config('app.name')}}</a>
        </p>
    </div>

    <div id="content">
        {!! $post->article->amp_content !!}
    </div>

    <h2>Bài viết liên quan</h2>
    <amp-list width="300" height="75" layout="responsive"
              src="{{config('app.url')}}/amp/related_posts/{{$post->id}}/json" binding="no">
        <template type="amp-mustache">
            <a class="related" href="<?php echo '{{full_path}}'; ?>">
                <amp-img width="101" height="75" src="<?php echo '{{image}}'; ?>"></amp-img>
                <span><?php echo '{{name}}'; ?></span></a>
        </template>
    </amp-list>

    <a href="{{$post->full_path}}">Đến trang chính</a>
</main>
</body>
</html>