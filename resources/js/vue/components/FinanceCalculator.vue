<template>
  <div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
      <div class="py-4 md:py-10 px-standard bg-gallery">
        <div class="mb-8 font-heading font-semibold text-2xl text-endeavour">
          Finance your {{ item }}
        </div>
        <div class="flex flex-row mb-8">
          <div class="bg-endeavour text-white p-2 text-center font-bold w-full">HP</div>
        </div>

        <div class="mb-8 flex justify-between text-lg font-medium">
          <span>Price (inc VAT)</span>
          <span class="text-endeavour">{{ formatPrice(price) }}</span>
        </div>

        <div class="mb-8">
          <div class="mb-4 flex justify-between text-lg font-medium">
            <span>Deposit</span>
            <span class="text-endeavour">{{ formatPrice(deposit) }}</span>
          </div>
          <vue-slider
            v-model="deposit"
            tooltip-placement="bottom"
            :contained="true"
            :marks="false"
            :hide-label="true"
            :interval="100"
            :max="maxDeposit"
            :tooltip-formatter="formatPrice"
          ></vue-slider>
        </div>

        <div class="mb-8">
          <div class="mb-4 flex justify-between text-lg font-medium">
            <span>Years</span>
            <span class="text-endeavour">{{ years }}</span>
          </div>
          <vue-slider
            v-model="years"
            tooltip-placement="bottom"
            :interval="1"
            :contained="true"
            :marks="true"
            :min="1"
            :max="maxTermYears"
            :hide-label="true"
          ></vue-slider>
        </div>

        <div class="mb-4 text-lg font-medium">
          Amount to finance
          <span class="font-bold">{{ formatPrice(financeAmount) }}</span>
        </div>

        <div class="mb-4 text-2xl">
          {{ numberOfPayments - 1 }} Monthly payments of
        </div>

        <div
          class="mb-4 p-2 text-4xl bg-endeavour text-white text-center font-bold"
        >
          {{ formatPrice(this.monthlyRepaymentAmount) }}
        </div>

        <div class="text-lg font-medium">
          APR:
          <span class="font-bold">{{ aprInterestRate }}%</span>
        </div>
      </div>

      <div class="py-4 md:py-10 px-standard bg-gallery">
        <div class="mb-8 font-heading font-semibold text-2xl text-endeavour">
          {{ financeType }} Breakdown
        </div>
        <div class="mb-6 flex flex-col md:flex-row justify-between text-lg">
          <span class="font-medium"
            >{{ numberOfPayments - 1 }} Monthly payments of</span
          >
          <span class="font-bold">{{
            formatPrice(this.monthlyRepaymentAmount)
          }}</span>
        </div>
        <div class="mb-6 flex flex-col md:flex-row justify-between text-lg">
          <span class="font-medium"> Price (inc VAT) </span>
          <span class="font-bold">
            {{ formatPrice(price) }}
          </span>
        </div>

        <div
          class="mb-6 flex justify-between text-lg"
          v-if="financeType === 'HP'"
        >
          <span class="font-medium">Final Repayment of</span>
          <span class="font-bold">{{
            formatPrice(this.finalPaymentAmount)
          }}</span>
        </div>

        <div class="mb-6 flex justify-between text-lg">
          <span class="font-medium">Deposit / Part Exchange</span>
          <span class="font-bold">{{ formatPrice(this.deposit) }}</span>
        </div>
        <div class="mb-6 flex flex-col md:flex-row justify-between text-lg">
          <span class="font-medium">Total Amount Payable</span>
          <span class="font-bold">{{
            formatPrice(this.totalRepaymentAmount)
          }}</span>
        </div>
        <div class="mb-6 flex flex-col md:flex-row justify-between text-lg">
          <span class="font-medium">Total Amount Of Credit</span>
          <span class="font-bold">{{ formatPrice(this.financeAmount) }}</span>
        </div>
        <div class="mb-6 flex flex-col md:flex-row justify-between text-lg">
          <span class="font-medium">Agreement Duration</span>
          <span class="font-bold">{{ numberOfPayments }} months</span>
        </div>
        <div class="mb-6 flex justify-between text-lg">
          <span class="font-medium">APR</span>
          <span class="font-bold">{{ aprInterestRate }}%</span>
        </div>
        <div class="mb-6 flex justify-between text-lg">
          <span class="font-medium">Interest Rate (Fixed)</span>
          <span class="font-bold">{{ fixedInterestRate }}%</span>
        </div>
      </div>
    </div>
    <div class="py-4 md:py-10 px-standard bg-white">
      <p class="mb-6 text-lg">
        Change the deposit and repayment period and the calculator will
        automatically work out your estimated repayments. The APR offered may
        vary according to a number of factors including deposit paid, status of
        the applicant, fees and charges and frequency of payments.
      </p>

      <a :href="creditIndicatorUrl" class="block mb-6" target="_blank">
        <img :src="creditIndicatorDesktopImageUrl" class="w-full hidden md:block" />
        <img :src="creditIndicatorMobileImageUrl" class="w-full md:hidden" />
      </a>

      <div class="text-sm" v-if="financeType === 'HP'">
        <p class="mb-6">
          Finance is available to UK residents aged 18 years or over, subject to
          status. Maximum term 10 years on Hire Purchase. A deposit may be
          required.
        </p>

        <p class="mb-6">
          This example does not constitute an offer of credit. Finance is
          subject to status. Terms and conditions apply. The actual
          documentation and/or other fees and charges that may apply may vary
          from time to time and from lender to lender.
        </p>

        <p class="mb-6">
          Registered Office Marquis Leisure | Part of the Auto-Sleepers Group
          Ltd | Orchard Works | Willersey | Nr Broadway | Worcestershire | WR12
          7QF | FCA Registered Number 673702
        </p>

        <p class="mb-6">
          Auto-Sleepers Group Ltd T/A Marquis Motorhomes and Caravans is
          registered with the Financial Conduct Authority as a credit broker
          (No, 308204)
        </p>

        <p class="mb-6">
          **Included in the final repayment. Marquis are a credit
          broker/intermediary and can introduce you to a limited number of
          lenders who provide funding. We may receive commission or other
          benefits for introducing you to such lenders. Finance available to UK
          residents aged 18 or over subject to application and status.
          Auto-Sleeper Group is regulated by the FCA with Limited permissions to
          conduct certain credit related activity. FCA Registered Number 673702.
        </p>
      </div>
      <div class="text-sm" v-else>
        <p class="mb-6">
          Finance is available to UK residents aged 18 years or over, subject to
          status. Maximum term 5 years on Personal Contract Purchase. A deposit
          may be required.
        </p>

        <p class="mb-6">
          This example does not constitute an offer of credit. Finance is
          subject to status. Terms and conditions apply. The actual
          documentation and/or other fees and charges that may apply may vary
          from time to time and from lender to lender.
        </p>

        <p class="mb-6">
          Registered Office Marquis Leisure | Part of the Auto-Sleepers Group
          Ltd | Orchard Works | Willersey | Nr Broadway | Worcestershire | WR12
          7QF | FCA Registered Number 673702
        </p>

        <p class="mb-6">
          Auto-Sleepers Group Ltd T/A Marquis Motorhomes and Caravans is
          registered with the Financial Conduct Authority as a credit broker
          (No, 308204)
        </p>

        <p class="mb-6">
          Marquis are a credit broker/intermediary and can introduce you to a
          limited number of lenders who provide funding. We may receive
          commission or other benefits for introducing you to such lenders.
          Finance available to UK residents aged 18 or over subject to
          application and status. Auto-Sleeper Group is regulated by the FCA
          with Limited permissions to conduct certain credit related activity.
          FCA Registered Number 673702.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import VueSlider from "vue-slider-component";
import HpCalculator from "../utilities/hp-calculator";

const generateInitialData = (initialPrice) => {
  return {
    price: initialPrice,
    deposit: 1000,
    aprInterestRate: 8.3,
    years: 10,
  };
};

export default {
  components: {
    VueSlider,
  },

  props: {
    item: {
      type: String,
      required: true,
    },
    currencyCode: {
      type: String,
      required: true,
    },
    locale: {
      type: String,
      required: true,
    },
    initialPrice: {
      type: Number,
      required: true,
    },
    creditIndicatorUrl: {
      type: String,
      required: true,
    },
    creditIndicatorDesktopImageUrl: {
      type: String,
      required: true,
    },
    creditIndicatorMobileImageUrl: {
      type: String,
      required: true,
    },
  },

  data: function () {
    let initialData = generateInitialData(this.initialPrice);

    return {
      financeType: "HP",
      deposit: initialData.deposit,
      aprInterestRate: initialData.aprInterestRate,
      years: initialData.years,
      price: initialData.price,
      numberFormatter: new Intl.NumberFormat(this.locale, {
        style: "currency",
        currency: this.currencyCode,
        minimumFractionDigits: 2,
      }),
    };
  },

  computed: {
    hpCalculator: function () {
      return new HpCalculator(
        this.price,
        this.deposit,
        this.aprInterestRate,
        this.years
      );
    },

    maxDeposit: function () {
      // round down to nearest thousand
      return this.price < 1000 ? 0 : Math.floor(this.price / 1000) * 1000;
    },

    maxTermYears: function () {
      return 10;
    },

    financeAmount: function () {
      return this.hpCalculator.getFinanceAmount().toFixed(2);
    },

    numberOfPayments: function () {
      return this.hpCalculator.getAgreementLengthMonths();
    },

    monthlyRepaymentAmount: function () {
      return this.hpCalculator.getMonthlyPaymentAmount().toFixed(2);
    },

    totalRepaymentAmount: function () {
      return this.hpCalculator.getTotalAmountPayable().toFixed(2);
    },

    finalPaymentAmount: function () {
      return this.hpCalculator.getFinalPaymentAmount().toFixed(2);
    },

    fixedInterestRate: function () {
      return this.hpCalculator.getFixedInterestRateAsPercentage().toFixed(2);
    },
  },

  methods: {
    formatPrice: function (price) {
      return this.numberFormatter.format(price);
    },
  },

  watch: {
    initialPrice: function (price) {
      this.price = price;
    },
    deposit: function (deposit) {
      this.hpCalculator.setDeposit(deposit);
    },
    years: function (years) {
      this.hpCalculator.setAgreementLengthYears(years);
    },
  },
};
</script>
