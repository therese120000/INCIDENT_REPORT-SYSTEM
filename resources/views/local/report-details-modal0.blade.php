<div id="reportDetailsModal" class="report-details-modal">

    <div class="report-details-modal-content">

        <div class="modal-header">

            <h2>Report Details</h2>

            <button
                type="button"
                id="closeReportDetailsModal"
                class="close-modal-btn"
            >
                &times;
            </button>

        </div>


        <!-- REPORT INFORMATION -->

        <div class="report-details-section">

            <h3>Report Information</h3>

            <div class="report-details-layout">

                <!-- PHOTO -->

                <div class="report-photo-section">

                    <div class="report-photo-wrapper">

                        <img
                            id="detailReportPhoto"
                            src=""
                            alt="Report Photo"
                            class="report-detail-photo"
                        >

                        <div
                            id="noReportPhoto"
                            class="no-report-photo"
                            style="display:none;"
                        >
                            No photo uploaded
                        </div>

                    </div>

                </div>


                <!-- INFORMATION -->

                <div class="report-information">

                    <div class="detail-row">
                        <span class="detail-label">
                            Report Number:
                        </span>

                        <span
                            id="detailReportNumber"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Incident Type:
                        </span>

                        <span
                            id="detailIncidentType"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Title:
                        </span>

                        <span
                            id="detailReportTitle"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Resident:
                        </span>

                        <span
                            id="detailResident"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Location:
                        </span>

                        <span
                            id="detailLocation"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Purok:
                        </span>

                        <span
                            id="detailPurok"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Water Level:
                        </span>

                        <span
                            id="detailWaterLevel"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Severity:
                        </span>

                        <span
                            id="detailSeverity"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Current Status:
                        </span>

                        <span
                            id="detailCurrentStatus"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>


                    <div class="detail-row">
                        <span class="detail-label">
                            Submitted:
                        </span>

                        <span
                            id="detailSubmittedAt"
                            class="detail-value"
                        >
                            -
                        </span>
                    </div>

                </div>

            </div>


            <div class="detail-description">

                <span class="detail-label">
                    Description:
                </span>

                <p id="detailDescription">
                    -
                </p>

            </div>

        </div>


        <!-- REPORT HISTORY -->

        <div class="report-details-section">

            <h3>Report Progress</h3>

            <div id="reportProgressTimeline">

                <div class="timeline-loading">
                    Loading report history...
                </div>

            </div>

        </div>


        <!-- ACTIONS -->

        <div class="modal-actions">

            <button
                type="button"
                id="closeReportDetails"
                class="cancel-btn"
            >
                Close
            </button>

        </div>

    </div>

</div>