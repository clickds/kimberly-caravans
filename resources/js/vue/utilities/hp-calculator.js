class HpCalculator {
  constructor (
    cashPrice,
    deposit,
    aprInterestRate,
    agreementLengthYears
  ) {
    this.cashPrice = cashPrice;
    this.deposit = deposit;
    this.aprInterestRate = aprInterestRate;
    this.agreementLengthYears = agreementLengthYears;
  }

  setDeposit(deposit) {
    this.deposit = deposit;
  }

  setAgreementLengthYears(years) {
    this.agreementLengthYears = years;
  }

  getFinanceAmount() {
    return this.cashPrice - this.deposit;
  }

  getAgreementLengthMonths() {
    return this.agreementLengthYears * 12;
  }

  getFixedInterestRate()
  {
    let baseValue = 1 + (this.aprInterestRate / 100);
    let percentageRate = Math.pow(baseValue, (1/12) ) - 1;
    let fixedInterestRate = percentageRate * 12;

    return fixedInterestRate;
  }

  getFixedInterestRateAsPercentage()
  {
    return this.getFixedInterestRate() * 100;
  }

  /**
   * P = (Pv*R) / [1 - (1 + R)^(-n)]
   *
   * P = Monthly Payment
   * Pv = Present Value (starting value of the loan)
   * APR = Annual Percentage Rate
   * R = Periodic Interest Rate = APR/number of interest periods per year
   * n = Total number of interest periods (interest periods per year * number of years)
   */
  getMonthlyPaymentAmount() {
    let presentValue = this.getFinanceAmount();
    let periodicInterestRate = this.getFixedInterestRate() / 12;
    let totalNumberOfInterestPeriods = this.agreementLengthYears * 12;
    let monthlyPaymentAmount = (presentValue * periodicInterestRate) / (1 - Math.pow( (1 + (periodicInterestRate) ), (-totalNumberOfInterestPeriods) ));

    return monthlyPaymentAmount;
  }

  getFinalPaymentAmount() {
    return this.getMonthlyPaymentAmount();
  }

  getTotalAmountPayable() {
    return (this.getMonthlyPaymentAmount() * this.getAgreementLengthMonths()) + this.deposit;
  }
}

export default HpCalculator;
