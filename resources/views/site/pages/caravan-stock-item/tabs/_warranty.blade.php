<div class="w-full container mx-auto lg:px-12">
  <div class="w-full py-5">
    @if ($stockItem->hasFeedSource())
      @if($stockItem->isNew() && $manufacturersWarrantyPage)
        <div class="text-endeavour font-semibold text-lg"><a class="text-shiraz" href="{{ $manufacturersWarrantyPage->link() }}">Click here</a> for warranty information.</div>
      @endif

      @if($stockItem->isUsed())
        <div class="mb-4">
          <h2 class="text-endeavour font-semibold">
            Industry leading CaraMarq 3 year guarantee
          </h2>

          @if($manufacturersWarrantyPage)
            <div class="text-endeavour font-semibold text-sm">Ex-display caravans come with the balance of the <a class="text-shiraz" href="{{ $manufacturersWarrantyPage->link() }}">manufacturer's standard warranty</a>.</div>
          @endif
        </div>

        @if ($warrantyPage)
          <a href="{{ $warrantyPage->link() }}">
            <img src="{{ url('/images/caramarq-3-year-guarantee-mobile.png') }}" class="mb-4 w-full lg:hidden" />
            <img src="{{ url('/images/caramarq-3-year-guarantee-desktop.png') }}" class="mb-4 hidden w-full lg:block" />
          </a>
        @else
          <img src="{{ url('/images/caramarq-3-year-guarantee-mobile.png') }}" class="mb-4 w-full lg:hidden" />
          <img src="{{ url('/images/caramarq-3-year-guarantee-desktop.png') }}" class="mb-4 hidden w-full lg:block" />
        @endif

        <p class="mb-4">
          Marquis pioneered the CaraMarq scheme for quality approved used caravans, and all of our
          used caravans receive a multi-point habitation inspection, prior to a full working
          demonstration on handover.
        </p>

        <ul class="list-disc list-inside -mx-2 mb-4 flex flex-wrap">
          <li class="px-2 w-full md:w-1/3">
            3 Year Habitation Guarantee*
          </li>
          <li class="px-2 w-full md:w-1/3">
            Multi-point Habitation Inspection**
          </li>
          <li class="px-2 w-full md:w-1/3">
            Supplied with a CRIS document
          </li>
          <li class="px-2 w-full md:w-1/3">
            Nationwide Service Network
          </li>
          <li class="px-2 w-full md:w-1/3">
            Priority Aftersales Appointments
          </li>
          <li class="px-2 w-full md:w-1/3">
            Professional Valet
          </li>
          <li class="px-2 w-full md:w-1/3">
            Full Working Demonstration
          </li>
          <li class="px-2 w-full md:w-1/3">
            Discount Accessory Voucher#
          </li>
        </ul>

        <p class="mb-4">
          All of our used vehicles can be purchased through one of our flexible funding options.
          Finance is subject to status, terms and conditions apply.
        </p>

        <h2 class="text-endeavour font-semibold mb-4">
          Disclaimer
        </h2>

        <p class="mb-4">
          Terms and conditions apply, further details available from your sales person.
        </p>

        <p class="mb-4">
          * The CaraMarq caravan you purchase may still be within its manufacturers warranty and
          this will take precedence over the CaraMarq guarantee. This guarantee is not available
          on vehicles over 15 years old.
        </p>

        <p class="mb-4">
          ** Multi point check including gas/electrical systems and damp check carried out by factory
          trained technicians to NCC standards.
        </p>

        <p class="mb-4">
          # Voucher redeemable at all Marquis branches and Service Centres
        </p>

        @if($warrantyPage)
          <p>
            Click <a href="{{ $warrantyPage->link() }}" class="text-endeavour hover:text-shiraz">here</a> for the full CaraMarq terms and conditions
          </p>
        @endif
      @endif
    @else
      @foreach($stockItem->warrantyFeatures($site) as $feature)
        <div class="mb-2 wysiwyg">
          {!! $feature->content !!}
        </div>
      @endforeach
    @endif
  </div>
</div>