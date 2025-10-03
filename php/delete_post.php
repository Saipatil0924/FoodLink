<?php
// Include the database connection file
include "db.php";

// Check if the form was submitted and if the donation_id is set
if (isset($_POST['delete_post']) && isset($_POST['donation_id'])) {

    // Get the donation ID from the submitted form
    $donation_id = $_POST['donation_id'];

    // Prepare the SQL DELETE statement to prevent SQL injection
    $sql = "DELETE FROM donations WHERE id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the integer parameter (the donation ID)
        $stmt->bind_param("i", $donation_id);

        // Execute the statement
        if (!$stmt->execute()) {
            // Handle potential errors during execution
            echo "Error deleting record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle potential errors during preparation
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();

    // Redirect the user back to the donor dashboard after deletion
    header("Location: ../donor_dashboard.php");
    exit();

} else {
    // If the page was accessed directly without submitting the form, redirect to the dashboard
    header("Location: ../donor_dashboard.php");
    exit();
}
?>