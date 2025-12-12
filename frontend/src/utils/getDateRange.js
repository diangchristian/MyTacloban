export  const getDateRange = (filter) => {
    const today = new Date();
    let start, end;
    switch (filter) {
      case "today":
        start = end = today.toISOString().split("T")[0];
        break;
      case "this_week":
        const firstDay = new Date(
          today.setDate(today.getDate() - today.getDay())
        );
        const lastDay = new Date(
          today.setDate(today.getDate() - today.getDay() + 6)
        );
        start = firstDay.toISOString().split("T")[0];
        end = lastDay.toISOString().split("T")[0];
        break;
      case "this_month":
        start = new Date(today.getFullYear(), today.getMonth(), 1)
          .toISOString()
          .split("T")[0];
        end = new Date(today.getFullYear(), today.getMonth() + 1, 0)
          .toISOString()
          .split("T")[0];
        break;
      default:
        start = end = null;
    }
    return { start, end };
  };