<div class="bg-gallery px-standard py-2 md:py-4">
  <div class="flex flex-wrap -mx-2">
    @if ($newsListingPage)
      <a href="{{ $newsListingPage->link() }}" class="w-full mb-2 flex items-center md:hidden">
        <div class="w-2/12 my-2 h-10 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.calendar')
        </div>
        <h3 class="w-9/12 px-4 text-regal-blue text-lg">
          Recent news
        </h3>
        <div class="w-1/12">
          <i class="fas fa-chevron-right text-regal-blue"></i>
        </div>
      </a>
      <a href="{{ $newsListingPage->link() }}" class="hidden md:flex flex-col text-center px-2 my-2 w-full md:w-1/2 lg:w-1/4 flex">
        <h3 class="text-regal-blue text-standard flex-grow-0">
          Recent news
        </h3>

        <div class="my-2 h-12 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.calendar')
        </div>

        <div class="flex-grow my-4 text-lg text-tundora font-sans font-medium">
          @if ($latestArticle)
            {{ $latestArticle->title }}
          @endif
        </div>

        <span class="text-endeavour hover:text-shiraz underline flex-grow-0">
          View all news
        </span>
      </a>
    @endif

    @if ($newsletterSignUpPage)
      <a href="{{ $newsletterSignUpPage->link() }}" class="w-full mb-2 flex items-center md:hidden">
        <div class="w-2/12 my-2 h-10 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.newsletter')
        </div>
        <h3 class="w-9/12 px-4 text-regal-blue text-lg flex-grow-0">
          Sign up to our newsletter
        </h3>
        <div class="w-1/12">
          <i class="fas fa-chevron-right text-regal-blue"></i>
        </div>
      </a>
      <a href="{{ $newsletterSignUpPage->link() }}" class="hidden md:flex flex-col text-center px-2 my-2 w-full md:w-1/2 lg:w-1/4">
        <h3 class="text-regal-blue flex-grow-0">
          Sign up to our newsletter
        </h3>

        <div class="my-2 h-12 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.newsletter')
        </div>

        <div class="flex-grow my-4 text-lg text-tundora font-sans font-medium">
          Get the latest offers, news, events and reviews sent direct to you
        </div>

        <span class="text-endeavour hover:text-shiraz underline flex-grow-0">
          Sign up
        </span>
      </a>
    @endif

    @if ($testimonialListingPage)
      <a href="{{ $testimonialListingPage->link() }}" class="w-full mb-2 flex items-center md:hidden">
        <div class="w-2/12 my-2 h-10 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.testimonials')
        </div>
        <h3 class="w-9/12 px-4 text-regal-blue text-lg flex-grow-0">
          Testimonials
        </h3>
        <div class="w-1/12">
          <i class="fas fa-chevron-right text-regal-blue"></i>
        </div>
      </a>
      <a href="{{ $testimonialListingPage->link() }}" class="hidden md:flex flex-col text-center px-2 my-2 w-full md:w-1/2 lg:w-1/4">
        <h3 class="text-regal-blue flex-grow-0">
          Testimonials
        </h3>

        <div class="my-2 h-12 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.testimonials')
        </div>

        <div class="flex-grow my-4 text-lg text-tundora font-sans font-medium">
          @if($latestTestimonial)
            {{ \Illuminate\Support\Str::limit(strip_tags($latestTestimonial->content), 80) }}
          @endif
        </div>

        <span class="text-endeavour hover:text-shiraz underline flex-grow-0">
          View all testimonials
        </span>
      </a>
    @endif

    @if ($tellUsYourStoryPage)
      <a href="{{ $tellUsYourStoryPage->link() }}" class="w-full mb-2 flex items-center md:hidden">
        <div class="w-2/12 my-2 h-10 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.loud-speaker')
        </div>
        <h3 class="w-9/12 px-4 text-regal-blue text-lg flex-grow-0">
          Tell us your story
        </h3>
        <div class="w-1/12">
          <i class="fas fa-chevron-right text-regal-blue"></i>
        </div>
      </a>
      <a href="{{ $tellUsYourStoryPage->link() }}" class="hidden md:flex flex-col text-center px-2 my-2 w-full md:w-1/2 lg:w-1/4">
        <h3 class="text-regal-blue flex-grow-0">
          Tell us your story
        </h3>

        <div class="my-2 h-12 flex justify-center text-shiraz flex-grow-0">
          @include('site.shared.svg-icons.loud-speaker')
        </div>

        <div class="flex-grow my-4 text-lg text-tundora font-sans font-medium">
          Share your latest adventures with us
        </div>

        <span href="{{ $tellUsYourStoryPage->link() }}" class="text-endeavour hover:text-shiraz underline flex-grow-0">
          Send us details
        </span>
      </a>
    @endif
  </div>
</div>