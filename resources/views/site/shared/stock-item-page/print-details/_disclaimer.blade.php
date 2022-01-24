<div class="text-monarch">
  <div class="border-monarch border-2 border-solid p-2">
    @if ($stockItem->isNew())
      <p class="mb-2">
        The OTR (On The Road) Price includes 6 months road fund licence, all delivery charges, first registration fee and number plates.
        This vehicle will undergo a full pre-delivery inspection prior to delivery in accordance with SMMT regulations.
        A full working demonstation will also be given upon collection.  All new vehicles are covered by a manufacturer's warranty - ask for details.
        Extended warranties with further cover are also available, please ask for futher information.
        A range of flexible payment plans are available for this vehicle, finance is subject to status.  Please ask for a written quotation.
      </p>
    @else
      <p class="mb-2">
        This vehicle will undergo a full pre-delivery inspection prior to delivery in accordance with SMMT regulations.
        A full working demonstation will also be given upon collection.  All used vehicles are covered by a 3 year guarantee.
        See policy booklet for details.  Extended guarantees with extra cover are also available, please ask for further information.
        A range of flexible payment plans are available for this vehicle, finance is subject to status.  Please ask for a written quotation.
      </p>
    @endif

    <p class="mb-2">
      <span class="uppercase">Please note: </span> Where fitted, radios have been removed for security reasons.  Alarms and radios are not covered by any warranty.
    </p>

    <p class="uppercase font-bold text-center">
      For further information please ask a member of our sales team.
    </p>
  </div>