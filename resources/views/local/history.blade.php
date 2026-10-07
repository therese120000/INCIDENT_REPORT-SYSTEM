<div class="page history-page" id="history">

    <div class="section-card">

        <div class="section-header">
            <div>
                <h2>Report History</h2>
                <p>All reports you have submitted.</p>
            </div>
        </div>

        <div class="history-tools">
            <input
                type="search"
                id="reportSearch"
                placeholder="Search reports..."
            >

            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="SUBMITTED">New Submitted</option>
                <option value="ACKNOWLEDGED">Acknowledged</option>
                <option value="IN_PROGRESS">In Progress</option>
                <option value="RESOLVED">Resolved</option>
            </select>
        </div>

        <div class="table-container">

            <table>

                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Issue</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="historyReportsBody">
                    <tr>
                        <td colspan="6">
                            Loading reports...
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div>

</div>

<div id="localReportDetailsModal" class="report-status-modal" style="display:none;">

    <div class="report-status-modal-content local-details-modal">

        <!-- HEADER -->
        <div class="modal-header">

            <div>
                <span class="modal-eyebrow">INCIDENT REPORT</span>
                <h2>Report Details</h2>
            </div>

            <button
                type="button"
                id="closeLocalReportDetails"
                class="close-modal-btn"
                aria-label="Close"
            >
                &times;
            </button>

        </div>


        <!-- PHOTO -->
        <div class="report-photo-section">

            <div class="report-photo-container">

                <img
                    id="localModalReportPhoto"
                    src=""
                    alt="Incident report photo"
                    style="display:none;"
                >

                <div
                    id="localNoReportPhoto"
                    class="no-report-photo"
                >
                    <div class="photo-placeholder-icon">
                        📷
                    </div>

                    <strong>No Photo Uploaded</strong>

                    <span>
                        No supporting image was attached to this report.
                    </span>
                </div>

            </div>

            <div class="photo-caption">
                <span>Incident Photo</span>
                <small>Supporting evidence submitted with the report</small>
            </div>

        </div>


        <!-- BASIC INFORMATION -->
        <div class="report-details">

            <div class="details-section-title">

                <div>
                    <span class="section-eyebrow">
                        REPORT INFORMATION
                    </span>

                    <h3>Incident Details</h3>
                </div>

            </div>


            <div class="details-grid">

                <!-- REPORT NUMBER -->
                <div class="detail-card">

                    <span class="detail-label">
                        Report Number
                    </span>

                    <span
                        id="localModalReportNumber"
                        class="detail-value report-number"
                    >
                        -
                    </span>

                </div>


                <!-- RESIDENT -->
                <div class="detail-card">

                    <span class="detail-label">
                        Resident
                    </span>

                    <span
                        id="localModalReportResident"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- INCIDENT TYPE -->
                <div class="detail-card">

                    <span class="detail-label">
                        Incident Type
                    </span>

                    <span
                        id="localModalIncidentType"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- TITLE -->
                <div class="detail-card">

                    <span class="detail-label">
                        Report Title
                    </span>

                    <span
                        id="localModalReportTitle"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- LOCATION -->
                <div class="detail-card">

                    <span class="detail-label">
                        Location
                    </span>

                    <span
                        id="localModalReportLocation"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- PUROK -->
                <div class="detail-card">

                    <span class="detail-label">
                        Purok
                    </span>

                    <span
                        id="localModalReportPurok"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- WATER LEVEL -->
                <div class="detail-card">

                    <span class="detail-label">
                        Water Level
                    </span>

                    <span
                        id="localModalReportWaterLevel"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- SEVERITY -->
                <div class="detail-card">

                    <span class="detail-label">
                        Severity
                    </span>

                    <span
                        id="localModalReportSeverity"
                        class="detail-value status-badge"
                    >
                        -
                    </span>

                </div>


                <!-- CURRENT STATUS -->
                <div class="detail-card">

                    <span class="detail-label">
                        Current Status
                    </span>

                    <span
                        id="localModalReportCurrentStatus"
                        class="detail-value status-badge"
                    >
                        -
                    </span>

                </div>


                <!-- SUBMITTED -->
                <div class="detail-card">

                    <span class="detail-label">
                        Submitted
                    </span>

                    <span
                        id="localModalReportSubmittedAt"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>

            </div>


            <!-- DESCRIPTION -->
            <div class="description-card">

                <span class="detail-label">
                    Description
                </span>

                <div
                    id="localModalReportDescription"
                    class="description-value"
                >
                    No description provided.
                </div>

            </div>


            <!-- PROGRESS -->
            <div class="report-progress">

                <div class="details-section-title">

                    <div>
                        <span class="section-eyebrow">
                            STATUS HISTORY
                        </span>

                        <h3>Report Progress</h3>
                    </div>

                </div>


                <div id="localReportProgressTimeline">

                    <div class="timeline-loading">
                        Loading updates...
                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="modal-actions local-modal-actions">

            <button
                type="button"
                id="closeLocalReportDetailsBottom"
                class="cancel-btn"
            >
                Close
            </button>

        </div>

    </div>

</div>

    <script src="{{ asset('js/viewReportStatus.js')}}"></script>
